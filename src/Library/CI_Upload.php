<?php

declare(strict_types=1);


namespace mudassar1\Legacy\Library;

//use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Images\Exceptions\ImageException;

//use CodeIgniter\Images\Image;


#use Config\Services;
use Illuminate\Support\Facades\Storage;
use mudassar1\Legacy\Exception\NotImplementedException;
use mudassar1\Legacy\Library\Upload\FileExtention;
use mudassar1\Legacy\Library\Upload\ValidationRuleMaker;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;


class CI_Upload
{
    /** @var ValidationRuleMaker */
    private $ruleMaker;

    /** @var array */
    private $ci3Config;

    /** @var UploadedFile|null */
    private $file;

    /** @var FileExtention */
    private $fileExt;

    /** @var string */
    private $newName;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(array $config = [])
    {
        $this->ci3Config = $config;
        $this->ruleMaker = new ValidationRuleMaker();
        $this->fileExt = new FileExtention();

        $this->checkNotImplementedConfig();
    }

    private function checkNotImplementedConfig()
    {
        // @TODO
        $notImplemented = [
            'file_name',
            'max_filename',
            'max_filename_increment',
            'remove_spaces',
            'detect_mime',
            'mod_mime_fix',
        ];

        foreach ($notImplemented as $item) {
            if (isset($this->ci3Config[$item])) {
                throw new NotImplementedException(
                    'config "' . $item . '" is not implemented yet.'
                );
            }
        }
    }

    /**
     * Perform the file upload.
     *
     * @return bool
     */
    public function do_upload(string $field = 'userfile')
    {
        $request = request();

//        dd(
//            'hi'
//        );
        if (!$request->hasFile($field)){
            return false;
        }

        // Validate the uploaded file based on the config rules
        $rules = $this->ruleMaker->convert($field, $this->ci3Config);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Retrieve the uploaded file from the request
        $this->file = $request->file($field);

        if ($this->file !== null && $this->file->isValid()) {
            $overwrite = $this->ci3Config['overwrite'] ?? false;

            // Set new file name, either encrypted or original
            $this->newName = $this->file->getClientOriginalName();
            if ($this->ci3Config['encrypt_name'] ?? false) {
                $this->newName = $this->file->hashName();
            }

            // Convert file extension to lowercase if required
            if ($this->ci3Config['file_ext_tolower'] ?? false) {
                $this->newName = $this->fileExt->toLower($this->newName);
            }

            // Move the file to the specified upload path
            $this->file->move($this->ci3Config['upload_path'], $this->newName);

            return true;
        }

        return false;
    }

    public function doUpload(string $uploadPath, string $field = 'userfile')
    {
        $request = request();

        // Validation Rules
//        $rules = [
//            $field => [
//                'required',
//                'file',
//                'max:10240', // Example: 10MB max file size
//                'mimes:jpeg,png,jpg,gif,doc,docx,pdf', // Allowed file types
//            ],
//        ];
//
//        // Validate the request
//        $validator = Validator::make(request()->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'success' => false,
//                'errors' => $validator->errors(),
//            ], 422);
//        }

        if (!$request->hasFile($field)){
            return false;
        }

        // Validate the uploaded file based on the config rules
        $rules = $this->ruleMaker->convert($field, $this->ci3Config);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        // Get the file from the request
        $file = request()->file($field);

        if ($file && $file->isValid()) {
            // Define new name based on the configuration
            $originalName = $file->getClientOriginalName();
            $newName = $originalName;

            // Encrypt the file name if required
//            if (config('files.upload.encrypt_name', false)) {
            $newName = $file->hashName();
//            }

            // Convert file extension to lowercase if required
//            if (config('files.upload.file_ext_tolower', false)) {
            $newName = strtolower($newName);
//            }

            // Upload to the S3 bucket
//            $uploadPath = storage_path('uploads'); // Default S3 folder
            $fullPath = $uploadPath . '/' . $newName;

            $uploaded = Storage::disk('linode')->putFileAs($uploadPath, $file, $newName);
//            Storage::disk('linode')->allFiles('/');

//            dd($uploaded, $uploadPath, $fullPath, $newName, Storage::disk('linode')->url($fullPath));
            if ($uploaded) {
//                return response()->json([
//                    'success' => true,
//                    'path' => $fullPath,
//                    'url' => Storage::disk('linode')->url($fullPath),
//                ]);
                return Storage::disk('linode')->url($fullPath);
            }

            return false;
//            response()->json([
//                'success' => false,
//                'message' => 'File upload failed.',
//            ], 500);
        }

        return false;
//        response()->json([
//            'success' => false,
//            'message' => 'Invalid file or file not provided.',
//        ], 400);
    }


    /**
     * Finalized Data Array.
     *
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @param string|null $index
     *
     * @return mixed
     */
    public function data(?string $index = null)
    {
        $full_path = realpath(
            $this->ci3Config['upload_path'] . '/' . $this->file->getClientOriginalName()
        );

        $data = $this->getImageData($full_path);

        if (!empty($index)) {
            return $data[$index] ?? null;
        }

        return $data;
    }

    /**
     * Get Image Data.
     *
     * @param string $full_path
     * @return array
     */
    private function getImageData(string $fullPath): array
    {
        // Ensure the full path is valid before trying to access it
        if (!file_exists($fullPath)) {
            throw new RuntimeException("File does not exist: {$fullPath}");
        }

        // Use the full path to get the size
        $fileSize = filesize($fullPath); // Use filesize instead of $this->file->getSize()

        if ($fileSize > 0) {
            $fileSize = round($fileSize / 1024, 2); // Convert to kilobytes
        }
        else {
            $fileSize = 0; // Set to 0 if not valid
        }


        // Get file extension and raw name
        $fileExt = pathinfo($fullPath, PATHINFO_EXTENSION); // Use full path for extension
        $rawName = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME); // This can still use original name

        return [
            'file_name'      => $this->newName,
            'file_type'      => $this->file->getClientMimeType(),
            'file_path'      => realpath($this->ci3Config['upload_path']),
            'full_path'      => $fullPath,
            'raw_name'       => $rawName,
            'orig_name'      => $this->file->getClientOriginalName(),
            'client_name'    => $this->file->getClientOriginalName(),
            'file_ext'       => '.' . $fileExt,
            'file_size'      => $fileSize,
            'is_image'       => $this->file->isValid() && strpos($this->file->getClientMimeType(), 'image/') === 0,
            'image_width'    => null, // Placeholder for image processing logic
            'image_height'   => null, // Placeholder for image processing logic
            'image_type'     => null, // Placeholder for image processing logic
            'image_size_str' => '', // Placeholder if needed
        ];
    }
}
