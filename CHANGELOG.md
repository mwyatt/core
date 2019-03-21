# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

breaking changes:

## [5.0.0] - 21-03-2019
### Added
### Changed
- in all controllers replace 'getRepository' with 'getService'
- watch out for index needing to 'setAdapters' should not need to
- no longer a 'RepositoryFactory' entry, find and remove all these
- hopefully can override the AbstractService construct to bring in exactly what needed
- all Repository now need to be Service - watch out for naming clashes in services.php
- when converting Repository to Service look at all '$this->' and change to new properties
- all Repository (now Service) need to be defined in services.php - hard code all dependencies in the constructor

### Fixed
### Deprecated
### Removed
### Fixed
### Security
## [4.3.3]
## [4.3.2]
## [4.3.1]
## [4.3.0]
## [4.2.0]
## [4.1.0]
## [4.0.2]
## [4.0.1]
## [4.0.0]
## [3.2.0]
## [3.1.1]
## [3.1.0]
## [3.0.3]
## [3.0.2]
## [3.0.1]
## [3.0.0]
## [2.15.0]
## [2.14.0]
## [2.13.1]
## [2.13.0]
## [2.12.2]
## [2.12.1]
## [2.12.0]
## [2.11.0]
## [2.10.2]
## [2.10.1]
## [2.10.0]
## [2.9.1]
## [2.9.0]
## [2.8.1]
## [2.8.0]
## [2.7.0]
## [2.6.0]
## [2.5.4]
## [2.5.3]
## [2.5.2]
## [2.5.1]
## [2.5.0]
## [2.4.0]
## [2.3.3]
## [2.3.2]
## [2.3.1]
## [2.3.0]
## [2.2.0]
## [2.1.1]
## [2.1.0]
## [2.0.0]
## [1.1.2]
## [1.1.1]
## [1.1.0]
## [1.0.1]
## [1.0.0]
## [0.0.0]
