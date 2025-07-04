<?php

namespace Guestcms\ACL\Repositories\Eloquent;

use Guestcms\ACL\Repositories\Interfaces\UserInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;

class UserRepository extends RepositoriesAbstract implements UserInterface
{
    public function getUniqueUsernameFromEmail(string $email): string
    {
        $emailPrefix = substr($email, 0, strpos($email, '@'));
        $username = $emailPrefix;
        $offset = 1;
        while ($this->getFirstBy(['username' => $username])) {
            $username = $emailPrefix . $offset;
            $offset++;
        }

        $this->resetModel();

        return $username;
    }
}
