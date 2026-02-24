<?php
namespace App\Services;

use App\Models\Nominee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NomineeService
{
    public function create(array $data, ?UploadedFile $image = null): Nominee
    {
        if ($image) {
            $data['image_path'] = $image->store('nominees', 'public');
        }

        return Nominee::create($data);
    }

    public function update(Nominee $nominee, array $data, ?UploadedFile $image = null): Nominee
    {
        if ($image) {
            if ($nominee->image_path && Storage::disk('public')->exists($nominee->image_path)) {
                Storage::disk('public')->delete($nominee->image_path);
            }

            $data['image_path'] = $image->store('nominees', 'public');
        }

        $nominee->update($data);

        return $nominee;
    }

    public function delete(Nominee $nominee): void
    {
        if ($nominee->image_path && Storage::disk('public')->exists($nominee->image_path)) {
            Storage::disk('public')->delete($nominee->image_path);
        }

        $nominee->delete();
    }
}
