<?php $v->layout("template/auth"); ?>
<div id="auth">

    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <h1 class="auth-title">Forgot your password</h1>
                <p class="auth-subtitle mb-5">Enter your email and we'll send you the password reset link.</p>
                <form method="post" autocomplete="off" id="form" action="<?= url("recover");?>">
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" class="form-control " placeholder="Email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Send</button>
                    <div class="carrega"></div>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Remember your account? <a href="<?= url("/login");?>" class="font-bold">Login</a>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>
</div>