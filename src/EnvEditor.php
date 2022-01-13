<?php

namespace GeoSot\EnvEditor;

use GeoSot\EnvEditor\Exceptions\EnvException;
use GeoSot\EnvEditor\Helpers\EnvFileContentManager;
use GeoSot\EnvEditor\Helpers\EnvFilesManager;
use GeoSot\EnvEditor\Helpers\EnvKeysManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Settings.
 */
class EnvEditor
{
    /**
     * @var array<string, string>
     */
    protected $config;

    /**
     * @var EnvKeysManager
     */
    protected $keysManager;

    /**
     * @var EnvFilesManager
     */
    protected $filesManager;

    /**
     * @var EnvFileContentManager
     */
    protected $fileContentManager;

    /**
     * @var string
     */
    protected $package = 'env-editor';

    /**
     * Constructor.
     * @param  array<string, string>  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->keysManager = new EnvKeysManager($this);
        $this->filesManager = new EnvFilesManager($this);
        $this->fileContentManager = new EnvFileContentManager($this);
    }

    /**
     * Parse the .env Contents.
     *
     * @param string $fileName
     *
     * @throws EnvException
     *
     * @return Collection
     */
    public function getEnvFileContent(string $fileName = ''): Collection
    {
        return $this->getFileContentManager()->getParsedFileContent($fileName);
    }

    /**
     * Check if key Exist in Current env.
     *
     * @param string $key
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function keyExists(string $key): bool
    {
        return $this->getKeysManager()->keyExists($key);
    }

    /**
     * Add the  Key  on the Current Env.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @throws EnvException
     *
     * @return mixed
     */
    public function getKey(string $key, $default = null)
    {
        return $this->getKeysManager()->getKey($key, $default);
    }

    /**
     * Add the  Key  on the Current Env.
     *
     * @param string $key
     * @param mixed  $value
     * @param array<string, int|string>  $options
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function addKey(string $key, $value, array $options = []): bool
    {
        return $this->getKeysManager()->addKey($key, $value, $options);
    }

    /**
     * Edits the Given Key  env.
     *
     * @param string $keyToChange
     * @param mixed  $newValue
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function editKey(string $keyToChange, $newValue): bool
    {
        return $this->getKeysManager()->editKey($keyToChange, $newValue);
    }

    /**
     * Deletes the Given Key form env.
     *
     * @param  string  $key
     *
     * @return bool
     * @throws EnvException
     */
    public function deleteKey(string $key): bool
    {
        return $this->getKeysManager()->deleteKey($key);
    }

    /**
     * Get all Backup Files.
     *
     * @throws EnvException
     */
    public function getAllBackUps(): Collection
    {
        return $this->getFilesManager()->getAllBackUps();
    }

    /**
     * uploadBackup.
     */
    public function upload(UploadedFile $uploadedFile, bool $replaceCurrentEnv): File
    {
        return $this->getFilesManager()->upload($uploadedFile, $replaceCurrentEnv);
    }

    /**
     * Used to create a backup of the current .env.
     * Will be assigned with the current timestamp.
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function backUpCurrent(): bool
    {
        return $this->getFilesManager()->backUpCurrentEnv();
    }

    /**
     * Returns the full path of a backup file. If $fileName is empty return the path of the .env file.
     *
     * @param string $fileName
     *
     * @throws EnvException
     *
     * @return string
     */
    public function getFilePath(string $fileName = ''): string
    {
        return $this->getFilesManager()->getFilePath($fileName);
    }

    /**
     * Delete the given backup-file.
     *
     * @param string $fileName
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function deleteBackup(string $fileName): bool
    {
        return $this->getFilesManager()->deleteBackup($fileName);
    }

    /**
     * Restore  the given backup-file.
     *
     * @param string $fileName
     *
     * @throws EnvException
     *
     * @return bool
     */
    public function restoreBackUp(string $fileName): bool
    {
        return $this->getFilesManager()->restoreBackup($fileName);
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function config(string $key, $default = null)
    {
        return config($this->package.'.'.$key, $default);
    }

    public function getKeysManager(): EnvKeysManager
    {
        return $this->keysManager;
    }

    public function getFilesManager(): EnvFilesManager
    {
        return $this->filesManager;
    }

    public function getFileContentManager(): EnvFileContentManager
    {
        return $this->fileContentManager;
    }
}
