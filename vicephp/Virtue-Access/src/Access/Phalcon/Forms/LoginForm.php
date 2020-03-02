<?php

namespace Virtue\Access\Phalcon\Forms;

use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Virtue\Access;

class LoginForm extends Form
{
    public function initialize()
    {
        $this->addUsername();
        $this->addPassword();
        $this->addToken();
        $this->addSubmit();
    }

    public function addUsername()
    {
        $username = new Element\Text(Access\Login::Username, ['required' => 'required']);
        $username->addValidator(new PresenceOf(['message' => 'Username is required']));

        $this->add($username);
    }

    public function addPassword()
    {
        $password = new Element\Password(Access\Login::Password, ['required' => 'required']);
        $password->addValidator(new PresenceOf(['message' => 'Password is required']));
        $password->clear();

        $this->add($password);
    }

    public function addToken()
    {
        $token = new Element\Hidden('token');
        $token->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));
        $token->clear();

        $this->add($token);
    }

    public function addSubmit()
    {
        $this->add(
            new Element\Submit('submit')
        );
    }
}
