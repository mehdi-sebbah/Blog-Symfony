<?php

namespace App\Uploader;

use App\Uploader\UploaderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader implements UploaderInterface 
{
    private SluggerInterface $slugger;

    private string $uploadsAbsoluteDir;
    
    private string $uploadsRelativeDir;

    public function __construct(SluggerInterface $slugger, string $uploadsAbsoluteDir, string $uploadsRelativeDir)
    {
        $this->slugger = $slugger;
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
        $this->uploadsRelativeDir = $uploadsRelativeDir;
    }

    public function upload(UploaderInterface $file): string
    {

        $filename = sprintf(
            "%s_%s.%s",
            $this->slugger->slug($file->getClientOriginalName()),
            uniqid(),
            $file->getClientOriginalExtension()
        );

        $file->move($this->uploadsAbsoluteDir, $filename);

        return $this->uploadsRelativeDir . "/" . $filename;
    }
}