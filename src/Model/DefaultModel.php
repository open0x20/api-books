<?php

namespace App\Model;

use App\Exception\BookException;
use App\Helper\ConfigHelper;
use App\Helper\LoggingHelper;
use ImagickException;

/**
 * Class DefaultModel
 * @package App\Model
 */
class DefaultModel
{
    /**
     * @param $filename
     * @return array
     * @throws BookException
     */
    public static function getBookData($filename)
    {
        $paths = ConfigHelper::get('paths');

        foreach ($paths as $path) {
            $file = $path . '/' . $filename;
            if (file_exists($file)) {
                $data = file_get_contents($file);
                if ($data === false) {
                    throw new BookException('Failed to read file \'' . $file . '\' (1).', 500);
                }

                return [
                    'data' => $data,
                    'extension' => (new \SplFileInfo($file))->getExtension()
                ];
            }
        }

        throw new BookException('No book with name \'' . $filename . '\' could be found.', 400);
    }

    /**
     * @param string $filename
     * @return array|string[]
     * @throws BookException
     * @throws ImagickException
     */
    public static function getCoverData(string $filename)
    {
        $dir = ConfigHelper::get('data_dir');
        $paths = ConfigHelper::get('paths');

        $coverFile = $dir . '/' . md5($filename);

        // Skip for non pdf files
        if (strpos($filename, '.pdf') === false) {
            throw new BookException('Cover generation for \'' . $filename . '\' is not supported.', 400);
        }

        // Cover already exists
        if (file_exists($coverFile)) {
            $data = file_get_contents($coverFile);
            if ($data === false) {
                throw new BookException('Failed to read file \'' . $coverFile . '\' (1).', 500);
            }
            return [
                'data' => $data
            ];
        }

        // Cover may need to be generated. Check if the file exists
        foreach ($paths as $path) {
            $file = $path . '/' . $filename;
            if (file_exists($file)) {
                $data = file_get_contents($file);
                if ($data === false) {
                    throw new BookException('Failed to read file \'' . $file . '\' (2).', 500);
                }

                $cover = new \Imagick($file . '[0]');
                $cover->setImageFormat('jpg');

                file_put_contents($coverFile, '' . $cover);
                return [
                    'data' => '' . $cover
                ];
            }
        }

        throw new BookException('No cover for book with name \'' . $filename . '\' could be found.', 400);
    }

    public static function listAllBooks()
    {
        $paths = ConfigHelper::get('paths');

        $books = [];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                LoggingHelper::getInstance()->warning('Could not load path: ' . $path);
                continue;
            }

            foreach (new \DirectoryIterator($path) as $file) {
                $filenameWithExtension = $file->getFilename();

                // Skip directories
                if ($file->isDir() || $file->isDot()) {
                    continue;
                }

                // Skip hidden files
                if ($filenameWithExtension[0] === '.') {
                    continue;
                }

                // Skip files without extensions
                if ($file->getExtension() === '') {
                    continue;
                }

                // Only allow specific extensions
                if (!in_array($file->getExtension(), ConfigHelper::get('allowed_types'))) {
                    continue;
                }

                $filename = explode('.' . $file->getExtension(), $filenameWithExtension)[0];
                $extension = $file->getExtension();

                $books[$filename][] = [
                    'type' => $extension,
                    'url' => '/book/' . $filenameWithExtension
                ];
            }
        }

        $formattedList = [];

        foreach ($books as $bookName => $bookTypes) {
            $formattedList[] = [
                'name' => $bookName,
                'types' => $bookTypes
            ];
        }

        return $formattedList;
    }
}
