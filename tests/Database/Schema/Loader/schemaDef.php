<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;

$schema = new Schema([]);
$schema->addTable(new Schema\Table([
    'name' => 'people',
    'columns' => [
        new Column\Integer('id', [
            'autoIncrement' => true,
            'size' => Column\Size::big()
        ]),
        new Column\Text('name', ['size' => Column\Size::tiny()]),
        new Column\Varchar('email', 255),
        new Column\Integer('level', ['size' => Column\Size::tiny(), 'default' => 4]),
        new Column\Decimal('score', 4, 2),
        new Column\Blob('picture', 1024),
        new Column\Boolean('active')
    ],
    'constraints' => [
        new Constraint\Primary('peoplePrimary', ['columnNames' => ['id']]),
        new Constraint\Unique('peopleUniqueEmail', ['column' => 'email'])
    ]
]))
    ->addTable(new Schema\Table([
        'name' => 'credentials',
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
    ->addTable(new Schema\Table([
        'name' => 'profiles',
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Text('language', ['size' => Column\Size::tiny()]),
            new Column\Text('timeZone', ['size' => Column\Size::tiny()]),
            new Column\Blob('picture', 1024),
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
    ->addTable(new Schema\Table([
        'name' => 'tags',
        'columns' => [
            new Column\Integer('id', [
                'autoIncrement' => true,
                'size' => Column\Size::big()
            ]),
            new Column\Varchar('name', 255),
            new Column\DateTime('created', ['nullable' => true]),
            new Column\DateTime('updated', ['nullable' => true])
        ],
        'constraints' => [
            new Constraint\Primary('tagsPrimary', ['columnNames' => ['id']]),
            new Constraint\Unique('tagsUniqueName', ['column' => 'name']),
        ]
    ]))
    ->addTable(new Schema\Table([
        'name' => 'posts',
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
    ->addTable(new Schema\Table([
        'name' => 'posts_tags',
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
    ->addTable(new Schema\Table([
        'name' => 'comments',
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