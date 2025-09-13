<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadImageTrait
{
    /**
     * Upload a single image file and update the specified model's column with the file path.
     *
     * @param  string  $inputName  The name of the file input field.
     * @param  string  $folder  The folder where the image will be stored.
     * @param  Model  $entry  The model instance to be updated.
     * @param  string  $columnName  The column in the database where the file path will be stored.
     */
    public function uploadImg(string $inputName, string $folder, Model $entry, string $columnName): void
    {
        if (request()->hasFile($inputName)) {
            $uploadedFile = request()->file($inputName);

            if ($uploadedFile instanceof UploadedFile) {
                // Handle new file upload
                $filename = Str::uuid().'.'.$uploadedFile->getClientOriginalExtension();
                $folderPath = 'uploads/'.$folder;

                $filePath = $uploadedFile->storeAs($folderPath, $filename, 'public');
                // Update the model with the new file path
                $entry->update([$columnName => $filePath]);
            }
        }
    }

    public function simpleUploadImg($file, string $folder, $previousImage = null, bool $useOriginalName = true)
    {
        if ($previousImage && Storage::disk('public')->exists($previousImage)) {
            Storage::disk('public')->delete($previousImage);
        }

        if ($file instanceof UploadedFile) {
            $filename = $useOriginalName
                ? $file->getClientOriginalName()
                : Str::uuid().'.'.$file->getClientOriginalExtension();

            $folderPath = 'uploads/'.$folder;

            return $file->storeAs($folderPath, $filename, 'public');
        }

        return null;
    }

    public function uploadMultipleImages(
        string $inputName,
        string $folder,
        Model $modelInstance,
        string $columnName,
        string $altTextColumn,
        ?array $altTexts = null,
        ?string $foreignKeyColumn = null,  // Foreign key column name
        ?int $foreignKeyValue = null  // Foreign key value
    ): void {
        if (request()->hasFile($inputName)) {
            foreach (request()->file($inputName) as $index => $uploadedFile) {
                if ($uploadedFile instanceof UploadedFile) {
                    // Use a unique identifier for the filename
                    $filename = Str::uuid().'.'.$uploadedFile->getClientOriginalExtension();
                    $folderPath = 'uploads/'.$folder;  // No need for a unique sub-folder here
                    $filePath = $uploadedFile->storeAs($folderPath, $filename, 'public');  // Use storeAs to set the filename

                    // Prepare data for creating a new entry
                    $data = [$columnName => $filePath];

                    // Add foreign key if provided
                    if ($foreignKeyColumn && $foreignKeyValue) {
                        $data[$foreignKeyColumn] = $foreignKeyValue;
                    }

                    // Only add alt text if it exists in the provided array
                    if ($altTexts && isset($altTexts[$index])) {
                        $data[$altTextColumn] = $altTexts[$index];
                    }

                    // Create a new entry for the uploaded image
                    $modelInstance::create($data);
                }
            }
        }
    }

    /**
     * Delete the previous image from storage.
     *
     * @param  string|null  $filePath  The path of the file to be deleted.
     */
    protected function deletePreviousImage(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
