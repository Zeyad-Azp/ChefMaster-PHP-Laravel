<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Regression tests for config/database.php fixes.
 *
 * Covers:
 *  - Bug 22: config/database.php references the unqualified `Mysql` class
 *            for PHP 8.5+ SSL handling; the `use Pdo\Mysql;` import must be
 *            present so Laravel can resolve `Mysql::ATTR_SSL_CA` once that
 *            branch becomes live.
 */
class DatabaseConfigRegressionTest extends TestCase
{
    /** Bug 22 — the Pdo\Mysql import must be in config/database.php. */
    public function test_database_config_imports_pdo_mysql(): void
    {
        $source = file_get_contents(base_path('config/database.php'));

        $this->assertStringContainsString(
            'use Pdo\\Mysql;',
            $source,
            'config/database.php must import Pdo\\Mysql so the PHP 8.5+ SSL ternary on the mysql/mariadb connections resolves.'
        );
    }

    /** Bug 22 — the file must load and resolve the mysql/mariadb connections. */
    public function test_mysql_and_mariadb_connections_resolve(): void
    {
        // Loading config/database.php for these keys would have thrown
        // "Class \"Mysql\" not found" on PHP 8.5+ before the import was restored.
        $mysql   = config('database.connections.mysql');
        $mariadb = config('database.connections.mariadb');

        $this->assertIsArray($mysql);
        $this->assertIsArray($mariadb);
        $this->assertSame('mysql',   $mysql['driver']);
        $this->assertSame('mariadb', $mariadb['driver']);
        $this->assertArrayHasKey('options', $mysql);
        $this->assertArrayHasKey('options', $mariadb);
    }
}
