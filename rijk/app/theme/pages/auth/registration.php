<?php $v->layout("template/auth"); ?>
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <h1 class="auth-title">Sign up</h1>
                <p class="auth-subtitle mb-5">Enter your details to register in our system.</p>
                <form method="post" autocomplete="off" id="form" action="<?= url("auth/cadastro"); ?>">
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control " placeholder="Name" required name="input_name" id="input_name">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control " placeholder="Email" required name="email" id="email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="phone" class="form-control " placeholder="Phone" required name="phone" id="phone">
                        <div class="form-control-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control " placeholder="Password" required name="password" id="password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <input type="hidden" name="roles" id="roles" value="2">
                    <!--                    <div class="form-group position-relative has-icon-left mb-4">-->
                    <!--                        <input type="password" class="form-control " placeholder="Confirme a Senha" required name="password_confirm" id="password_confirm">-->
                    <!--                        <div class="form-control-icon">-->
                    <!--                            <i class="bi bi-shield-lock"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign up</button>
                    <div class="carrega"></div>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Already have an account? <a href="<?= url("login"); ?>" class="font-bold">Login</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
</div>