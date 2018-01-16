<?php
use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Config\Adapter\Json;
use Phalcon\Db\Reference as Reference;
use Phalcon\Mvc\Model\Migration;
use \TrackingApi\Application;

class UserRolesMigration_2 extends Migration
{
    public function up() {
        $config = new Json(dirname(__DIR__) . '\..\..\..\app\config.json');
        $this->morphTable(
            'user_roles',
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type'          => Column::TYPE_INTEGER,
                            'size'          => 10,
                            'unsigned'      => true,
                            'notNull'       => true,
                            'autoIncrement' => true,
                            'first'         => true,
                        ]
                    ),
                    new Column(
                        'role',
                        [
                            'type'          => Column::TYPE_VARCHAR,
                            'size'          => 255,
                            'notNull'       => true,
                        ]
                    ),
                    new Column(
                        'grant_all',
                        [
                            'type'    => Column::TYPE_INTEGER,
                            'size'    => 1,
                            'notNull' => true,
                            'after'   => 'updated_at',
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after'   => 'expires_at',
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after'   => 'created_at',
                        ]
                    ),
                    new Column(
                        'active',
                        [
                            'type'    => Column::TYPE_INTEGER,
                            'size'    => 1,
                            'notNull' => true,
                            'after'   => 'updated_at',
                        ]
                    ),
                ],
                'indexes' => [
                    new Index(
                        'PRIMARY',
                        [
                            'id',
                        ]
                    ),
                ]
            ]
        );

        self::$_connection->addForeignKey(
            'users',
            self::getDbName(),
            new Reference(
                'user_role_fk',
                [
                    "referencedSchema"  => self::getDbName(),
                    'referencedTable'   => 'user_roles',
                    'columns'           => [
                        'role_id',
                    ],
                    'referencedColumns' => [
                        'id',
                    ],
                ]
            )
        );
    }
}