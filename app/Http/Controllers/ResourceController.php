<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ResourceController extends Controller
{

    public $genreController;

    public function __construct(Promotion $model, GenreController $genreController)
    {
        parent::__construct($model);
        $this->genreController = $genreController;
    }

    public function index()
    {
        return $this->success('Successfully retrieved data', $this->model->with('genres')->get());
    }

    public function show(string $id)
    {
        return $this->success('Successfully retrieved data', $this->model->with('genres')->findOrFail($id));
    }

    public function homepage()
    {
        $data['title'] = 'Homepage';
        $response = $this->index();
        $decoded = $response->getData(true);
        $dramas = collect($decoded['data'])->map(function ($drama) {
            //decode image dari string JSON
            if (is_string($drama['image'])) {
                $drama['image'] = json_decode($drama['image'], true);
            }

            if (isset($drama['genres']) && is_string($drama['genres'])) {
                $drama['genres'] = json_decode($drama['genres'], true);
            }

            return $drama;
        })->toArray();


        $data['title'] = 'Homepage';
        $data['dramas'] = $dramas;
        // dd($data);

        $response = $this->genreController->index();
        $decoded = $response->getData(true);
        $genres = $decoded['data'];
        $data['genres'] = $genres;
        // dd($data);

        return view('homepage', $data);
    }

    public function addDrama()
    {
        $data['title'] = 'Add drama';
        $response = $this->genreController->index();
        $decoded = $response->getData(true);
        $genres = $decoded['data'];
        $data['genres'] = $genres;
        return view('addDrama', $data);
    }
    public function saveNewDrama(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:promotions,title',
            'description' => 'required|string|max:10000',
            'image' => 'required|array|size:2',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5024',
            'genres' => 'required|json',

        ]);

        if ($validator->fails()) {
            Log::error('Validation Error', $validator->errors()->toArray());
            throw new ValidationException($validator, response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422));
        }


        $titleWords = explode(' ', trim($request->title));
        $lastTwoWords = array_slice($titleWords, -2);
        $slugWords = implode('_', array_map(fn($w) => preg_replace('/[^a-z0-9]/i', '', strtolower($w)), $lastTwoWords));
        $uniq = uniqid();

        $paths = [];

        foreach ($request->file('image') as $index => $img) {
            $suffix = $index === 0 ? 'P' : 'L';
            $ext = $img->getClientOriginalExtension();
            $filename = "{$uniq}_{$slugWords}_{$suffix}.{$ext}";

            $img->storeAs('dramas', $filename, 'public');

            $paths[] = str_replace('\\', '/', "dramas/$filename");
        }

        $genreArray = json_decode($request->genres, true);

        $promotion = Promotion::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => json_encode($paths, JSON_UNESCAPED_SLASHES),
        ]);

        $promotion->genres()->attach($genreArray); //simpan ke drama_genres(pivot))

        return $this->success('Drama berhasil ditambahkan');
    }

    public function dramaDetail($id)
    {
        $response = $this->show($id);
        $decoded = $response->getData(true);
        $drama = $decoded['data'];

        if (is_string($drama['image'])) {
            $drama['image'] = json_decode($drama['image'], true);
        }

        if (isset($drama['genres']) && is_string($drama['genres'])) {
            $drama['genres'] = json_decode($drama['genres'], true);
        }

        $data['details'] = $drama;
        $data['title'] = '' . $drama['title'];

        return view('detailDrama', $data);
    }

    public function editDrama($id)
    {
        $response = $this->show($id);
        $decoded = $response->getData(true);
        $drama = $decoded['data'];

        if (is_string($drama['image'])) {
            $drama['image'] = json_decode($drama['image'], true);
        }

        if (isset($drama['genres']) && is_string($drama['genres'])) {
            $drama['genres'] = json_decode($drama['genres'], true);
        }

        $data['details'] = $drama;
        $data['title'] = 'Edit ' . $drama['title'];
        $response = $this->genreController->index();
        $decoded = $response->getData(true);
        $genres = $decoded['data'];
        $data['genres'] = $genres;
        // dd($data);
        return view('editDrama', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'image.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5024',
            'genre'       => 'required|array|min:1',
        ]);

        $drama = Promotion::findOrFail($id);

        $existingImages = $request->input('existing_images', []);
        $oldImagesInDB = is_array($drama->image) ? $drama->image : json_decode($drama->image, true);


        //cari posisi index yang dihapus
        $deletedIndexes = [];
        foreach ($oldImagesInDB as $index => $imgPath) {
            if (!in_array($imgPath, $existingImages)) {
                $deletedIndexes[] = $index;
                // Hapus file fisik
                if (Storage::exists('public/' . $imgPath)) {
                    Storage::delete('public/' . $imgPath);
                }
            }
        }

        // Siapkan slug & uniqid untuk nama file
        $titleWords = explode(' ', trim($request->title));
        $lastTwoWords = array_slice($titleWords, -2);
        $slugWords = implode('_', array_map(fn($w) => preg_replace('/[^a-z0-9]/i', '', strtolower($w)), $lastTwoWords));
        $uniq = uniqid();

        //array final, posisi awal dari existing
        $finalImages = $oldImagesInDB;

        //upload gambar baru ke posisi yang sesuai
        if ($request->hasFile('image')) {
            $newFiles = $request->file('image');
            foreach ($newFiles as $i => $file) {
                if (!isset($deletedIndexes[$i])) break;

                $targetIndex = $deletedIndexes[$i];
                $suffix = $targetIndex === 0 ? 'P' : 'L';
                $ext = $file->getClientOriginalExtension();
                $filename = "{$uniq}_{$slugWords}_{$suffix}.{$ext}";

                $file->storeAs('dramas', $filename, 'public');

                $finalImages[$targetIndex] = str_replace('\\', '/', "dramas/$filename");
            }
        }

        $finalImages = array_values(array_filter($finalImages));

        $drama->title = $request->input('title');
        $drama->description = $request->input('description');
        $drama->image = $finalImages;
        $drama->genres()->sync($request->input('genre'));
        $drama->save();
        session()->flash('success', 'Drama berhasil diedit!');
        return redirect()->route('drama.edit', ['id' => $id]);
    }

    //destroy pakai func di parent controller.php

}
