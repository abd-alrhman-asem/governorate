<?php
//
//namespace App\FileHandlingManager;
//
//use Exception;
//use Illuminate\Support\Str;
//use Illuminate\Http\UploadedFile;
//
//class ComplaintFileManager
//{
//    protected string $
//   disk = 'complaint';
//
//
//    public function __construct()
//    {
//    }
//
//    /**
//     * Store one or more complaint-related files and return paths.
//     *
//     * @param UploadedFile|UploadedFile[]|null $files
//     * @param int $userId
//     * @param int $complaintId
//     * @return string[]
//     * @throws Exception
//     */
//    public function storeComplaintFiles(
//        UploadedFile|array|null $files,
//        int                     $userId,
//        int                     $complaintId
//    ): array
//    {
//        $path = "attachments/{$userId}/{$complaintId}";
//        return $this->uploadFiles($files, $path);
//    }
//
//
//    /**
//     * @throws Exception
//     */
//    public function uploadFiles(UploadedFile|array|null $files, string $path): array
//    {
//        if (is_null($files)) return [];
//
//        $storedPaths = [];
//        if ($files instanceof UploadedFile) {
//            $storedPaths[] = $this->checkStoreRenameSingleFile($files, $path);
//        } elseif (is_array($files)) {
//            foreach ($files as $file) {
//                $storedPaths[] = $this->checkStoreRenameSingleFile($file, $path);
//            }
//        }
//        return $storedPaths;
//    }
//
//    function checkStoreRenameSingleFile($file, $path): false|string
//    {
//        if ($file instanceof UploadedFile) {
//            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
//            return $file->storeAs(
//                $path,
//                $filename,
//                $this->disk
//            );
//        } else
//            throw new Exception('file upload error');
//    }
//}
