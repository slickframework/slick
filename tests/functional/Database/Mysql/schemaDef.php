<?php

/**
 * Schema definition file
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;

$schema = new Schema([]);

$schema->addTable(new Schema\Table('people', [
    'columns' => [
        new Column\Integer('id', [
            'autoIncrement' => true,
            'size' => Column\Size::big()
        ]),
        new Column\Text('name', ['size' => Column\Size::tiny()]),
        new Column\Varchar('email', 255)
    ],
    'constraints' => [
        new Constraint\Primary('peoplePrimary', ['columnNames' => ['id']]),
        new Constraint\Unique('peopleUniqueEmail', ['column' => 'email'])
    ]
]))
    ->addTable(new Schema\Table('credentials', [
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Varchar('username', 255),
            new Column\Varchar('password', 64),
            new Column\Integer('person_id', ['size' => Column\Size::big()]),
            new Column\Varchar('email', 255)
        ],
        'constraints' => [
            new Constraint\Primary('credentialsPrimary', ['columnNames' => ['id']]),
            new Constraint\Unique('credentialsUniqueUsername', ['column' => 'username']),
            new Constraint\ForeignKey(
                'credentialPersonFk',
                'person_id',
                'people',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            )
        ]
    ]))
    ->addTable(new Schema\Table('profiles', [
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Text('language', ['size' => Column\Size::tiny()]),
            new Column\Text('timeZone', ['size' => Column\Size::tiny()]),
            new Column\Text('picture', ['size' => Column\Size::tiny()]),
            new Column\Integer('person_id', ['size' => Column\Size::big()]),
        ],
        'constraints' => [
            new Constraint\Primary('profilesPrimary', ['columnNames' => ['id']]),
            new Constraint\ForeignKey(
                'profilePersonFk',
                'person_id',
                'people',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            )
        ]
    ]))
    ->addTable(new Schema\Table('tags', [
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Varchar('name', 255),
            new Column\DateTime('created'),
            new Column\DateTime('updated')
        ],
        'constraints' => [
            new Constraint\Primary('tagsPrimary', ['columnNames' => ['id']]),
            new Constraint\Unique('tagsUniqueName', ['column' => 'name']),
        ]
    ]))
    ->addTable(new Schema\Table('posts', [
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Text('title', ['size' => Column\Size::tiny()]),
            new Column\Text('body', ['size' => Column\Size::long()]),
            new Column\DateTime('created'),
            new Column\Integer('person_id', ['size' => Column\Size::big(), 'nullable' => true]),
        ] ,
        'constraints' => [
            new Constraint\Primary('tagsPrimary', ['columnNames' => ['id']]),
            new Constraint\ForeignKey(
                'postPersonFk',
                'person_id',
                'people',
                'id',
                ['onDelete' => Constraint\ForeignKey::SET_NULL]
            )
        ]
    ]))
    ->addTable(new Schema\Table('posts_tags', [
        'columns' => [
            new Column\Integer('post_id', ['size' => Column\Size::big()]),
            new Column\Integer('tag_id', ['size' => Column\Size::big()]),
        ],
        'constraints' => [
            new Constraint\Primary(
                'tagsPostsPrimary',
                ['columnNames' => ['post_id', 'tag_id']]
            ),
            new Constraint\ForeignKey(
                'postsTagsPostFk',
                'post_id',
                'posts',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            ),
            new Constraint\ForeignKey(
                'postsTagsTagFk',
                'tag_id',
                'tags',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            ),
        ]
    ]))
    ->addTable(new Schema\Table('comments', [
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Text('body', ['size' => Column\Size::medium()]),
            new Column\Integer('person_id', ['size' => Column\Size::big()]),
            new Column\Integer('post_id', ['size' => Column\Size::big()]),
        ],
        'constraints' => [
            new Constraint\Primary('commentsPrimary', ['columnNames' => ['id']]),
            new Constraint\ForeignKey(
                'commentsPostFk',
                'post_id',
                'posts',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            ),
            new Constraint\ForeignKey(
                'commentsPersonFk',
                'person_id',
                'people',
                'id',
                ['onDelete' => Constraint\ForeignKey::CASCADE]
            ),
        ]
    ]));

return $schema;