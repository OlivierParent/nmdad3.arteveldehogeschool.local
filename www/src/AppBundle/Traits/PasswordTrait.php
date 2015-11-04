<?php

namespace AppBundle\Traits;

use AppBundle\Entity\User;

trait PasswordTrait
{
    /**
     * @param User $user
     */
    public function hashPassword(User $user)
    {
        $container = method_exists($this, 'getContainer') ? $this->getContainer() : $this;
        $passwordEncoder = $container->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPasswordRaw());
        $user->setPassword($encodedPassword);
    }
}