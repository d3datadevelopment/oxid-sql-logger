# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.3.3...master)

## [1.3.3](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.3.2...1.3.3) - 2022-09-05
### Fixed
- fix argument error if no parameter list was set in stopDebug

## [1.3.2](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.3.1...1.3.2) - 2022-07-18
### Fixed
- fix some code style issues

## [1.3.1](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.3.0...1.3.1) - 2021-04-29
### Fixed
- prevent the use of not countable parameters in prepared statemant rendering 

## [1.3.0](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.2.2...1.3.0) - 2021-03-02
### Fixed
- fix missing static methods

### Added
- add configurable FirePHP handler

## [1.2.2](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.2.1...1.2.2) - 2021-01-23
### Changed
- change static methods to non-static methods for better testable code
- change line endings for easy patching

### Added
- add prepared statement generator

## [1.2.1](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.2.0...1.2.1) - 2020-05-01
### Fixed
- add missing autoload file item in composer.json

## [1.2.0](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.1.0...1.2.0) - 2020-04-30
### Added
- Format SQL query 
- Measures the SQL execution time

## [1.1.0](https://git.d3data.de/D3Public/oxid-sql-logger/compare/1.0.0...1.1.0) - 2019-09-20
### Added
- add log message
- add position specifications of the calling source code 

## [1.0.0](https://git.d3data.de/D3Public/oxid-sql-logger/releases/tag/1.0.0) - 2019-07-21

available in tumtum/oxid-sql-logger package only

### Added
- Global function `StartSQLLog` and `StopSQLLog`
- Display SQL Querys on Browser or CLI