CHANGELOG
=========
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### [Unreleased]
#### Added
- Nothing to report.
  
#### Fixed
- Nothing to report.

#### Changed
- Nothing to report.

#### Deprecated
- Nothing to report.

#### Removed
- Nothing to report.

#### Security
- Nothing to report.

### [1.0.0] - 2019-09-18
#### Added
- Default configuration file generate by publish artisan command.
  See [#951a844](https://github.com/tbitencourt/laravel-repository-eloquent/commit/951a8445b018ef54b46b34f4bb316e840e92971c).
- First version of RepositoryEloquent abstract class using Illuminate\Database\Eloquent\Builder as base.
  See [#ff330aa](https://github.com/tbitencourt/laravel-repository-eloquent/commit/ff330aac6f189ae7cacd27d748f3409209bbf8c7).
- MIT License.
  See [#12c1d48](https://github.com/tbitencourt/laravel-repository-eloquent/commit/12c1d48ed736a5e830778530d9e6a408b6397550).
- Add function "customWhere" on RepositoryEloquent, to filter using a array with "where" parameters.
  See [#c1a2f8d](https://github.com/tbitencourt/laravel-repository-eloquent/commit/c1a2f8d3dcf729acdaf92a94440a4f2a9f4efd0e).

### [1.1.0] - 2019-11-18
#### Added
- Added laravel framework 6.0 (or later).
  See [#29b810f](https://github.com/tbitencourt/laravel-repository-eloquent/commit/29b810fadd3a1b59372080dc346ba68c8736f509).

#### Fixed
- Config filename fixed.
  See [#055a094](https://github.com/tbitencourt/laravel-repository-eloquent/commit/055a0941f35cd9a3b1ff79e5351d33ed1e5eba06).
- The "customWhere" function on RepositoryEloquent wasn't accepting more then one relation's filter. It has been fixed.
  See [#78f63a5](https://github.com/tbitencourt/laravel-repository-eloquent/commit/78f63a55f4d87c12579afdd7f631cd14c48cc016).
