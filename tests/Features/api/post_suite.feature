Feature: Post a suite
    As a developer
    In order to be able to ananalyze my benchmark results over time
    I want to be able to submit my results to Phpbench Reports

    Scenario: Submit suite
        Given I post the following payload to "/api/v1/suite":
        """
        {
            "133c976408973745350e29a346b8d0d6d5d5bfd2\\PhpBench\\Benchmarks\\Micro\\HashingBenchbenchMd50": {
                "suite": "133c976408973745350e29a346b8d0d6d5d5bfd2",
                "contextName": null,
                "date": "2017-11-26T15:44:41+01:00",
                "timestamp": "1511707481",
                "configPath": "\/home\/daniel\/www\/phpbench\/phpbench\/phpbench.json",
                "env": {
                    "uname": {
                        "os": "Linux",
                        "host": "dtlx1",
                        "release": "4.10.0-38-generic",
                        "version": "#42-Ubuntu SMP Tue Oct 10 13:24:27 UTC 2017",
                        "machine": "x86_64"
                    },
                    "php": {
                        "xdebug": true,
                        "version": "7.1.11-1+ubuntu17.04.1+deb.sury.org+1",
                        "ini": "\/etc\/php\/7.1\/cli\/php.ini",
                        "extensions": "Core, date, libxml, openssl, pcre, zlib, filter, hash, pcntl, Reflection, SPL, session, standard, mysqlnd, PDO, xml, calendar, ctype, curl, dom, mbstring, fileinfo, ftp, gd, gettext, iconv, igbinary, intl, json, exif, mongodb, mysqli, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, Phar, posix, readline, redis, shmop, SimpleXML, sockets, sqlite3, sysvmsg, sysvsem, sysvshm, tokenizer, wddx, xmlreader, xmlwriter, xsl, zip, Zend OPcache, xdebug"
                    },
                    "opcache": {
                        "extension_loaded": true,
                    },
                    "enabled": false
                    "unix-sysload": {
                        "l1": 1.16,
                        "l5": 1.25,
                        "l15": 1.22
                    },
                    "vcs": {
                        "system": "git",
                        "branch": "reports_driver",
                        "version": "cdb74330368ef95fd2a13d1247d199163ff1d9fa"
                    },
                    "baseline": {
                        "nothing": 0.05984306335449219,
                        "md5": 0.5409717559814453,
                        "file_rw": 1.3511180877685547
                    }
                },
                "class": "\\PhpBench\\Benchmarks\\Micro\\HashingBench",
                "subject": "benchMd5",
                "groups": [],
                "sleep": 0,
                "retry_threshold": null,
                "output_time_unit": null,
                "output_time_precision": null,
                "output_mode": null,
                "index": 0,
                "parameters": [],
                "nb_iterations": 1,
                "rejects": [],
                "revolutions": 1000,
                "warmup": 0,
                "stats": {
                    "min": 0.933,
                    "max": 0.933,
                    "sum": 0.933,
                    "stdev": 0,
                    "mean": 0.933,
                    "mode": 0.933,
                    "variance": 0,
                    "rstdev": 0
                }
            }
        }
        """
        Then the response status code should be 200

    Scenario: Submit iterations
        Given I post the following payload to "/api/v1/iterations":
        """
{
    "133c976408973745350e29a346b8d0d6d5d5bfd2\\PhpBench\\Benchmarks\\Micro\\HashingBenchbenchMd500": {
        "index": 0,
        "results": {
            "time": {
                "net": 933
            },
            "mem": {
                "peak": 1257024,
                "real": 2097152,
                "final": 1202704
            },
            "comp": {
                "z_value": 0,
                "deviation": 0
            }
        },
        "iteration": 0,
        "suite": "133c976408973745350e29a346b8d0d6d5d5bfd2",
        "variant": 0,
        "subject": "benchMd5",
        "benchmark": "\\PhpBench\\Benchmarks\\Micro\\HashingBench"
    },
    "133c976408973745350e29a346b8d0d6d5d5bfd2\\PhpBench\\Benchmarks\\Micro\\HashingBenchbenchSha100": {
        "index": 0,
        "results": {
            "time": {
                "net": 1322
            },
            "mem": {
                "peak": 1257024,
                "real": 2097152,
                "final": 1202704
            },
            "comp": {
                "z_value": 0,
                "deviation": 0
            }
        },
        "iteration": 0,
        "suite": "133c976408973745350e29a346b8d0d6d5d5bfd2",
        "variant": 0,
        "subject": "benchSha1",
        "benchmark": "\\PhpBench\\Benchmarks\\Micro\\HashingBench"
    },
    "133c976408973745350e29a346b8d0d6d5d5bfd2\\PhpBench\\Benchmarks\\Micro\\HashingBenchbenchSha25600": {
        "index": 0,
        "results": {
            "time": {
                "net": 1286
            },
            "mem": {
                "peak": 1257024,
                "real": 2097152,
                "final": 1202704
            },
            "comp": {
                "z_value": 0,
                "deviation": 0
            }
        },
        "iteration": 0,
        "suite": "133c976408973745350e29a346b8d0d6d5d5bfd2",
        "variant": 0,
        "subject": "benchSha256",
        "benchmark": "\\PhpBench\\Benchmarks\\Micro\\HashingBench"
    }
}
        """
        Then the response status code should be 200
