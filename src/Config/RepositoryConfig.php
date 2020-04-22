<?php

/**
 * PHP version 7.4
 * @category PHP
 * @package  LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */

declare(strict_types=1);

namespace Tbitencourt\LaravelRepositoryEloquent\Config;

use Exception;
use Tbitencourt\LaravelRepositoryEloquent\Exceptions\RepositoryException;

/**
 * Class RepositoryConfig
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent\Config
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */
class RepositoryConfig
{
    /**
     * @var string
     */
    protected $repositoryDefaultPath;
    /**
     * @var string
     */
    protected $repositoryGroupPath;
    /**
     * @var string
     */
    protected $repositorySuffixName;
    /**
     * @var string
     */
    protected $modelDefaultPath;
    /**
     * @var string
     */
    protected $modelGroupPath;
    /**
     * @var string
     */
    protected $modelSuffixName;

    /**
     * RepositoryConfig constructor.
     * @throws RepositoryException
     */
    public function __construct()
    {
        try {
            $this->configModels();
            $this->configRepositories();
        } catch (Exception $ex) {
            throw new RepositoryException('Erro ao carregar configurações de Repository!');
        }
    }

    /**
     * @return void
     */
    protected function configModels(): void
    {
        $modelConfig = config('perfectlib.meta.defaults.models', []);
        if (isset($modelConfig['default_path']) && !isset($this->modelDefaultPath)) {
            $this->modelDefaultPath = $modelConfig['default_path'] ?? 'Models';
        }
        if (isset($modelConfig['group_path']) && !isset($this->modelGroupPath)) {
            $this->modelGroupPath = $modelConfig['group_path'] ?? true;
        }
        if (isset($modelConfig['suffix_name']) && !isset($this->modelSuffixName)) {
            $this->modelSuffixName = $modelConfig['suffix_name'] ?? '';
        }
    }

    /**
     * @return void
     */
    protected function configRepositories(): void
    {
        $repositoryConfig = config('perfectlib.meta.defaults.repositories', []);
        if (isset($repositoryConfig['default_path']) && !isset($this->repositoryDefaultPath)) {
            $this->repositoryDefaultPath = $repositoryConfig['default_path'] ?? 'Repositories';
        }
        if (isset($repositoryConfig['group_path']) && !isset($this->repositoryGroupPath)) {
            $this->repositoryGroupPath = $repositoryConfig['group_path'] ?? true;
        }
        if (isset($repositoryConfig['suffix_name']) && !isset($this->repositorySuffixName)) {
            $this->repositorySuffixName = $repositoryConfig['suffix_name'] ?? 'Repository';
        }
    }

    /**
     * @return string
     */
    public function getRepositoryDefaultPath(): string
    {
        return $this->repositoryDefaultPath;
    }

    /**
     * @return string
     */
    public function getRepositoryGroupPath(): string
    {
        return $this->repositoryGroupPath;
    }

    /**
     * @return string
     */
    public function getRepositorySuffixName(): string
    {
        return $this->repositorySuffixName;
    }

    /**
     * @return string
     */
    public function getModelDefaultPath(): string
    {
        return $this->modelDefaultPath;
    }

    /**
     * @return string
     */
    public function getModelGroupPath(): string
    {
        return $this->modelGroupPath;
    }

    /**
     * @return string
     */
    public function getModelSuffixName(): string
    {
        return $this->modelSuffixName;
    }
}
