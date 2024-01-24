<?php $v->layout("template/error");?>
    <div id="error">
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <img class="img-error" src="<?= url("/theme/assets/images/samples/error-403.png");?>" alt="Not Found">
                <div class="text-center">
                    <h1 class="error-title">Oooops Erro <?= $error; ?></h1>
                    <p class="fs-5 text-gray-600">Erro ao acessar esta página.</p>
                    <a href="<?= url();?>" class="btn btn-lg btn-outline-primary mt-3">Voltar</a>
                </div>
            </div>
        </div>
    </div>