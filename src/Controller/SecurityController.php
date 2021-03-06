<?php


namespace Project\Controller;

use Component\Controller;
use Component\Session;
use Project\Repository\User;
use Project\Validator\UserValidator;

class SecurityController extends Controller
{
    /**
     * @return string
     */
    public function signIn(): string
    {
        $session = Session::instance();

        if ($session->userId()) {
            $this->redirect('/');
        }

        $error = '';

        if ($this->request->isPost()) {
            $email = $this->request->post('email');
            $plainPassword = $this->request->post('plainPassword');

            /* @var $repository User */
            $repository = $this->createRepository(User::class);

            /* @var $user \Project\Entity\User */
            $user = $repository->findByEmail($email);

            if ($user) {
                if (password_verify($plainPassword, $user->password)) {
                    $session->add('email', $user->email);
                    $session->add('user_id', $user->id);
                    $this->redirect('/');
                }
            }
            $error = 'Неверный логин или пароль';
        }

        return $this->render('security/sign-in.php', [
            'error' => $error
        ]);
    }

    /**
     * @return string
     */
    public function signUp(): string
    {
        $session = Session::instance();

        if ($session->userId()) {
            $this->redirect('/');
        }

        $errors = [];

        if ($this->request->isPost()) {
            $email = $this->request->post('email');
            $plainPassword = $this->request->post('plainPassword');

            /* @var $repository User */
            $repository = $this->createRepository(User::class);

            $validator = new UserValidator($repository);
            $validator->validate($email, $plainPassword);

            if ($validator->isValid()) {
                $repository->create($email, $plainPassword);
                $session->add('message', 'Вы успешно зарегистрировались');
                $this->redirect('/sign-in');
            }

            $errors = $validator->getErrors();
        }

        return $this->render('security/sign-up.php', [
            'errors' => $errors
        ]);
    }

    public function signOut(): void
    {
        Session::instance()->destroy();
        $this->redirect('/');
    }
}
