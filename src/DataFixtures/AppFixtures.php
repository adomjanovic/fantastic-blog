<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\LikeCounter;
use App\Entity\Post;
use App\Entity\PostDetail;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Nelmio\Alice\Loader\NativeLoader;

/**
 * DataFixtures class to fill up DB with some dummy data for testing
 *
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();

        $objectSet = $loader->loadData([
            User::class => [
                'user_{1..9}' => [
                    'firstname' => '<username()>',
                    'lastname' => '<lastName()>',
                    'display_name' => '<firstName()> <lastName()>',
                    'registration_date' => '<date_create()>',
                    'plain_password' => '1234',
                    'email' => '<email()>',
                    'roles' => '[ROLE_USER]',
                    'enabled' => 1,
                ],
                'user_admin' => [
                    'firstname' => 'Ante',
                    'lastname' => 'Domjanovic',
                    'display_name' => '<firstName()> <lastName()>',
                    'registration_date' => '<date_create()>',
                    'plain_password' => '1234',
                    'email' => 'admin@fb.com',
                    'roles' => '[ROLE_ADMIN]',
                    'enabled' => 1,
                ],
            ],
            Post::class => [
                'post_{1..9}' => [
                    'title' => '<sentence()>',
                    'post_detail' => '@post.post_detail_<current()>',
                    'status' => '[enabled]',
                    'tags' => '3x @tag_*',
                    'author' => '@user_admin*'
                ],
            ],
            Tag::class => [
                'tag_{1..5}' => [
                    'name' => 'Tag-<current()>',
                ],
            ],
            PostDetail::class => [
                'post.post_detail_{1..9}' => [
                    'content' => '<paragraph()>',
                    'post' => '@post_<current()>'
                ],
            ],

            Comment::class => [
                'comment_{1..50}' => [
                    'content' => '<sentence()>',
                    'post' => '@post_*',
                    'author' => '@user_*',
                    'created' => '<dateTimeBetween("-6 months", "now")>'
                ],
            ],
            LikeCounter::class => [
                'like_counter_{1..9}' => [
                    'owner' => '@user_*',
                    'post' => '@post_*',
                    'value' => '<numberBetween(1, 50)>'
                ],
            ],
            Favorite::class => [
                'favorite_{1..9}' => [
                    'user' => '@user_*',
                    'post' => '@post_*'
                ],
            ],
        ])->getObjects();

        foreach($objectSet as $object) {
            $manager->persist($object);
        }
        $manager->flush();

    }
}