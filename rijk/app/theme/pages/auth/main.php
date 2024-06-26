<?php $v->layout("template/auth"); ?>
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <h1 class="auth-title font-bold">Login.</h1>
                <p class="auth-subtitle mb-5">Log in with your data you entered during registration.</p>
                <form method="post" id="form" action="<?= url("auth/login"); ?>">
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control " placeholder="Email" name="email" id="email" required>
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control " placeholder="Password" name="password" id="password" required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5 bg-green border-green hover:bg-greenDark hover:border-greenDark">Log in</button>
                    <div class="carrega"></div>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">Don't have an account? <a href="cadastro" class="font-bold">Sign up</a>.</p>
                    <p><a class="font-bold" href="<?= url("recover-password"); ?>">Forgot your password?</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
</div>