/**
 * Validação da moeda atual
 */
var formatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
    minimumFractionDigits: 2
});
/**
 * Shake on validator
 */

var link_site = $('body').attr('data-link_site');
var estado_site = $('body').attr('data-estado_site');
jQuery.fn.shake = function(interval, distance, times) {
    interval = typeof interval == "undefined" ? 100 : interval;
    distance = typeof distance == "undefined" ? 10 : distance;
    times = typeof times == "undefined" ? 3 : times;
    var jTarget = $(this);
    jTarget.css('position', 'relative');
    for (var iter = 0; iter < (times + 1); iter++) {
        jTarget.animate({
            left: ((iter % 2 == 0 ? distance : distance * -1))
        }, interval);
    }
    return jTarget.animate({
        left: 0
    }, interval);
}
$(document).ready(function() {
    $(".lds-roller").delay(1000).fadeOut("slow");
    $("#overlayer").delay(1000).fadeOut("slow");
    $('.tipo_pagamento').hide();
    $("#cupomCal").hide();


    // var mediaDevices = navigator.mediaDevices;
    // let mediaRecorder
    // mediaDevices.getUserMedia({audio: true})
    //     .then( stream => {
    //         mediaRecorder = new MediaRecorder(stream)
    //         mediaRecorder.ondataavailable = data => {
    //         }
    //         mediaRecorder.onstop = () => {
    //         }
    //         mediaRecorder.start() //setTimeOut(() => mediaRecorder.stop(), 3000)
    //     }, err =>{
    //     })
})

/**
 * Validação de campos
 */
$('.select2-single').select2();
$(".selectMes").select2({
    minimumResultsForSearch: -1
});
$('#telefone, #telefoneVeri').mask('(00) 00000-0000');
$('#cpf').mask('000.000.000-00', {
    reverse: true
});
$('#cep, #cep_end').mask('00000-000');
$('#trocoCli, #dinheiro').mask('#.##0,00', {
    reverse: true
});

$("#emailOurTel").on('blur touchleave touchcancel', function() {
    let regra = /^[0-9]+$/;
    let valor = $(this).val();
    if (valor.match(regra)) {
        $(this).mask('(00) 00000-0000');
    } else {}
});


$(".avaliacao_motoboy, .avaliacao_pedido").starRating({
    totalStars: 5,
    starShape: 'rounded',
    starSize: 40,
    emptyColor: 'lightgray',
    strokeColor: '#894A00',
    useGradient: false,
    callback: function(currentRating, el) {
        $(`#${el[0].className}`).val(currentRating);
    }
});

/**
 * Link ativo no portal
 */
var active_link = window.location.pathname;
switch (active_link) {
    case `/${link_site}/perfil`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/endereco`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;

    case `/${link_site}/motoboy/pegar/entrega`:
        $('#entrega').addClass('selected')
        $('#entrega a, #entrega a i').addClass('text-danger').removeClass('text-dark')
        break;

    case `/${link_site}/motoboy`:
        $('#home').addClass('selected')
        $('#home a, #home a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/motoboy/`:
        $('#home').addClass('selected')
        $('#home a, #home a i').addClass('text-danger').removeClass('text-dark')
        break;

    case `/${link_site}/motoboy/perfil`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;

    case `/${link_site}/motoboy/dados-veiculo`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;

    case `/${link_site}/enderecos`:
        $('#endereco').addClass('selected')
        $('#endereco a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/endereco/novo`:
        $('#endereco').addClass('selected')
        $('#endereco a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/favoritos`:
        $('#favoritos').addClass('selected')
        $('#favoritos a, #favoritos a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/meus-pedidos`:
        $('#pedidos').addClass('selected')
        $('#pedidos a, #pedidos a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/`:
        $('#home').addClass('selected')
        $('#home a, #home a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/privacidade`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/delivery`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/fale-conosco`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    case `/${link_site}/termos`:
        $('#perfil').addClass('selected')
        $('#perfil a, #perfil a i').addClass('text-danger').removeClass('text-dark')
        break;
    default:
        $('#pedidos').addClass('selected')
        $('#pedidos a, #pedidos a i').addClass('text-danger').removeClass('text-dark')
}

/**
 * 
 * Funções antigas
 */
$("#abrirCarrinhoModal").on('click', function() {
    $("#modal-carrinho").modal("show");
});
$('#trocoMod, #mp, #entrega_end, #dinMod').hide();

$('#tipo_pagamento').on('change', function() {
    $('#tipo_pagamento').css("border", "1px solid #ced4da");
    if (parseInt($(this).val()) === 1) {
        $('#trocoMod').show();
        $('.btnValida').hide();
        $('.tipo_pagamento').hide();
    } else {
        $('#trocoMod').hide();
        $('#trocoCliente').hide();
        $('.btnValida').show();
        $('.tipo_pagamento').show();
    }

    if (parseInt($(this).val()) === 7) {
        $('#dinMod').show();
    } else {
        $('#dinMod').hide();
    }
});

$('#calcularTroco').on('click', function() {
    let valor = parseFloat($('#trocoCli').val());
    let total_pago = parseFloat($('#total_pago').val());
    let totalFinal = valor - total_pago

    if (valor === "" || valor < total_pago) {
        $('#mensagem').html('O seu troco precisa ser maior que o total do seu pedido!');
        $('.errorSup, .buttonAlert').show();
        $('.successSup').hide();
        $('#alertGeralSite').modal("show");
        return false
    } else {
        if (isNaN(valor)) {
            let totalFinal2 = total_pago - total_pago
            $('#troco').val(total_pago)
            $('.btnValida').show()
            $('#trocoCliente').show();
            $('#trocoCliente span').text(formatter.format(totalFinal2))
            $('.tipo_pagamento').show()
        } else {
            $('#troco').val(valor)
            $('.btnValida').show()
            $('#trocoCliente').show();
            $('#trocoCliente span').text(formatter.format(totalFinal))
            $('.tipo_pagamento').show()
            $("html, body").animate({
                scrollTop: $(document).height()
            }, "slow");
        }
        return false
    }
});


$('#tipo_frete').on('change', function() {
    if (parseInt($(this).val()) === 2) {
        $('#entrega_end').hide();
        $('#entregaForm').show();
        $('#freteCal').show();
        $('#taxa_mod').show();

        var a = $('#total_pago').val();
        var b = $('#valor_frete').val();
        var d = (parseFloat(a) + parseFloat(b));

        $('#valorProdutoMostra').text(formatter.format(d));
        $('#total_pago, #pagCartao').val(d);

    } else if (parseInt($(this).val()) === 1) {
        $('#entrega_end').show();
        $('#entregaForm').hide();
        $('#freteCal').hide();
        $('#taxa_mod').hide();

        var a = $('#total_pago').val();
        var b = $('#valor_frete').val();
        var d = (parseFloat(a) - parseFloat(b));

        $('#valorProdutoMostra').text(formatter.format(d));
        $('#total_pago, #pagCartao').val(d);
    }
});

/**
 * 
 * Carrinho de Compra produtos
 */
$('.adicional_item_ok').hide();
var vi = $("#add_itens").length;
var qb = $("#add_itens #itens_value").text();
var iqba = $("#add_itens #itens_value").text();

(qb === "0") ? $('.adicional_item, .adicional_item_ok').hide(): $('.adicional_item').show();

$('#add_itenSabores input[type=radio]').on('change', function(e) {
    if ($(this).is(":checked")) {
        $(".addStyle").show()
    } else {
        $(".addStyle").hide()
    }
    $(this).closest('.hovereffect').toggleClass('clic');
});

//Pizza Massa

$("#btn_pedido").hide()
$("#add_itenPizza input[type=checkbox]").prop("disabled", true);
$('#add_itenMassa input[type=checkbox], #add_itenMassa input[type=radio]').on('change', function(e1) {
    let valor = $(this).attr('data-valor')
    let iCount = $('#iCount').text()

    let id = $(this).val()
    let totalMassa = $('#totalMassa').val()
    let totalPizza = $(`#totalPizza`).val()

    $('#massa').val(id);
    if (parseFloat(totalMassa) === 0) {
        $('#total').text(formatter.format(valor));
        $('#totalMassa').val(valor);
    } else {
        $('#totalMassa').val(valor);
        $('#total').text(formatter.format(parseFloat(totalPizza) + parseFloat(totalMassa)));
        $('#valor').val(parseFloat(totalPizza) + parseFloat(totalMassa));
    }

    $('#massa_choice').html(`<span class="statusCart">Pronto <i class="feather-check"></i></span>`)
    $("#add_itenPizza input[type=checkbox]").prop("disabled", false);
});

$('#add_itenPizza input[type=radio]').on('change', function(e1) {
    $('#saborCount').text(1)
    let id = $(this).val()
    let valor = $(this).attr('data-valor')
    let totalMassa = $('#totalMassa').val()
    let iCount = $('#iCount').text()

    $('#total').text(formatter.format(parseFloat(valor) + parseFloat(totalMassa)));
    $(`#totalPizza`).val(valor);
    $('#valor').val(parseFloat(valor) + parseFloat(totalMassa));
    $('#id_produto').val(id);
    $("#btn_pedido").show()

    $('#saborCounts').addClass('greens');
    $('#pizza_choice').html(`<span class="statusCart">Pronto <i class="feather-check"></i></span>`);

});

$('#add_itenPizza input[type=checkbox]').on('change', function(e1) {
    $('#saborCount').text(1)
    let iCount = parseInt($('#iCount').text())
    let id = $(this).val()
    let totalPizza = parseFloat($(`#totalPizza`).val())
    let valor = $(this).attr('data-valor')
    let totalMassa = $('#totalMassa').val()

    var total = 0;
    var qtd = 0;
    var a = [];

    $('#add_itenPizza input[type=checkbox]:checked').each(function(i, ele) {
            var valor = parseFloat($(this).attr('data-valor'));
            a.push(valor);
            qtd = i + 1;
            $('#saborCount').text(qtd)
        }
        //total += valor;
    );
    //console.log(a);
    var total = Math.max.apply(null, a);
    //console.log(total);

    $('#totalPizza').val(total);
    $('#valor').val(parseFloat(total) + parseFloat(totalMassa));
    $('#total').text(formatter.format(parseFloat(total) + parseFloat(totalMassa)));

    if (qtd === iCount) {
        $("#saborCount").text(qtd);
        $('#saborCounts').addClass('greens');
        $('#pizza_choice').html(`<span class="statusCart">Pronto <i class="feather-check"></i></span>`);
        $("input[type=checkbox]").prop("disabled", true);
        $("input[type=checkbox]:checked").prop("disabled", false);
        $("#btn_pedido").show()
    } else {
        $("input[type=checkbox]").prop("disabled", false);
    }

    $('#id_produto').val(id);


});
//product Adicional
$('.mdc-card input[type=checkbox]').on('change', function(e1) {
    //loadTeachers($(e1.target).val());
    //console.log(e1);
    let tipoSlug = $(this).attr('data-tiposlug')
    let idEnvBlock = $(`#${tipoSlug}`).attr('id')
    let tipo_escolha = $(`#${idEnvBlock}`).attr('data-tipo_escolha')
    let tipo_escolhaQtd = $(`#${idEnvBlock}`).attr('data-qtd')
    var a = $(`#${idEnvBlock} input[type=checkbox]:checked`).length;
    var adv = $(this).val();
    //console.log(adv);
    let adv_att = $(this).attr('id');
    let vrec = $(this).attr('valor');
    let vb = $('#valorFinal').val();
    var a = $(`#${idEnvBlock} input[type=checkbox]:checked`).length;

    if (parseInt(tipo_escolha) === 2) {
        if (parseInt(a) === parseInt(tipo_escolhaQtd)) {
            $(`#${idEnvBlock} .adicional_item, .addStyleMod, .addStyleModT`).hide();
            $(`#${idEnvBlock} .adicional_item_ok, .addStyle`).show();
            if (parseInt(a) === 1) {
                $(`#${idEnvBlock} .adicional_item_ok`).text(`${a} de ${tipo_escolhaQtd} selecionado`);
            }
            if (parseInt(a) <= parseInt(tipo_escolhaQtd)) {
                $(`#${idEnvBlock} .adicional_item_ok`).text(`${a} de ${tipo_escolhaQtd} selecionados`);
            }
        } else {
            $(`#${idEnvBlock} .adicional_item`).show();
            $(`#${idEnvBlock} .adicional_item_ok, .addStyle, .addStyleMod, .addStyleModT`).hide();
        }

        if (parseInt(a) > parseInt(tipo_escolhaQtd)) {
            $(this).prop('checked', false);
            $(this).closest('label').removeClass('active');
            if (parseInt(tipo_escolhaQtd) > 1) {
                alert("Você só pode selecionar " + parseInt(tipo_escolhaQtd) + " itens");
                return;
            } else {
                alert("Você só pode selecionar " + parseInt(tipo_escolhaQtd) + " item");
                return;
            }
        }
    }

    if (parseInt(tipo_escolha) === 3) {
        if (parseInt(a) > 0 && parseInt(a) <= parseInt(tipo_escolhaQtd)) {
            $(`#${idEnvBlock} .adicional_item, .addStyleMod, .addStyleModT`).hide();
            $(`#${idEnvBlock} .adicional_item_ok`).show();
            if (parseInt(a) === 1) {
                $(`#${idEnvBlock} .adicional_item_ok`).text(`${a} de ${tipo_escolhaQtd} selecionado`);
            }
            if (parseInt(a) <= parseInt(tipo_escolhaQtd)) {
                $(`#${idEnvBlock} .adicional_item_ok`).text(`${a} de ${tipo_escolhaQtd} selecionados`);
            }
            $(".addStyle").show()
        } else {
            $(`#${idEnvBlock} .adicional_item`).show();
            $(`#${idEnvBlock} .adicional_item_ok`).hide();
        }

        if (parseInt(a) > parseInt(tipo_escolhaQtd)) {
            $(this).prop('checked', false);
            $(this).closest('label').removeClass('active');
            if (parseInt(tipo_escolhaQtd) > 1) {
                alert("Você só pode selecionar " + parseInt(tipo_escolhaQtd) + " itens");
                return;
            } else {
                alert("Você só pode selecionar " + parseInt(tipo_escolhaQtd) + " item");
                return;
            }
        }
    }

    if ($(this).is(":checked")) {
        $(`.${adv_att}`).show();
        var vc = parseFloat(vb) + parseFloat(vrec);
        var id_adicional = $(`#id_adicional${adv}`).val();
        var quantidade = 1;
        var numero_pedido = $('#numero_pedido').val();
        var id_produto = $('#id_produto').val();
        var id_carrinho = $('#id_carrinho').val();
        var id_empresa = $('#id_empresa').val();
        var id_customere = $('#id_customere').val();
        var valor = $(`#valor${adv}`).val();
        $.ajax({
            url: `/${link_site}/product/addCarrinho/adicionais`,
            type: 'post',
            data: {
                id_adicional: id_adicional,
                id_produto: id_produto,
                id_customere: id_customere,
                id_carrinho: id_carrinho,
                valor: valor,
                quantidade: quantidade,
                numero_pedido: numero_pedido,
                id_empresa: id_empresa,
                chave: chave
            },
            success: function(dd) {
                console.log(dd)
                var valorAdicional = $(`#id_adicional${adv}`).attr('valor');
                var novoValor = (parseFloat(vb) + parseFloat(valorAdicional));
                $('#total').text(formatter.format(novoValor));
                $('#valorFinal').val(novoValor);
            },
            error: function(xhr) {}
        });
    } else {
        $(`.${adv_att}`).hide();
        $(`.${adv}`).hide();
        $(".addStyle").show();

        var chave = $('#chave').val();
        var id_carrinho = $('#id_carrinho').val();
        var id_empresa = $('#id_empresa').val();
        var id_customere = $('#id_customere').val();

        $.ajax({
            url: `/${link_site}/product/removeCarrinho/adicionais`,
            type: 'get',
            data: {
                chave: chave,
                id_carrinho: id_carrinho,
                id_adicional: adv,
                id_empresa: id_empresa,
                id_customere: id_customere
            },
            success: function(dd) {
                console.log(dd)
                var novoValor = (parseFloat(vb) - parseFloat(vrec));
                if (!isNaN(novoValor)) {
                    $('#total').text(formatter.format(novoValor));
                }
                $(`#qtd_ad${adv}`).val(1);
            },
            error: function(xhr) {}
        });
        $(".addStyle").show()
    }
    $(this).closest('.hovereffect').toggleClass('clic');
    var valorAdicional = $(`#id_adicional${adv}`).attr('valor');
    if (parseFloat(valorAdicional) === 0.00) {
        $(`.id_adicional${adv}`).hide();
    }

    var total = 0;
    $('[data-qtd]').each(function(i, ele) {
        let tipo_escolha = $(`#${ele['id']}`).attr('data-tipo_escolha')
        let tipoQtd = $(`#${ele['id']}`).attr('data-qtd')
        if (parseInt(tipo_escolha) === 2) {
            var valor = Number(tipoQtd);
            if (!isNaN(valor)) total += valor;
        }
        if (parseInt(tipo_escolha) === 3) {
            total = total + 1;
        }
    });

    total = total

    let c = $(`input[type=checkbox]:checked`).length;
    if (parseInt(c) >= total) {
        $(`.addStyle`).show()
    } else {
        $(`.addStyle`).hide()
    }

    if (parseInt(tipo_escolha) === 1) {
        $(`.addStyleMod, .addStyleModT`).hide();
        $(`.addStyle`).show();
    }
});

$('[data-quantity="plus"]').click(function(e) {
    console.log(e);
    $('.minuss').prop("disabled", false);
    fieldName = $(this).attr('data-field');
    var currentVal = parseInt($('input[name=' + fieldName + ']').val());

    if (!isNaN(currentVal)) {
        $('input[name=' + fieldName + ']').val(currentVal + 1);
        var advS = $(this).attr('id-select');
        var vbA = $('#valorFinal').val();
    } else {
        $('input[name=' + fieldName + ']').val(0);
    }

    var id_adicional = $(`#id_adicional${advS}`).val();
    var quantidade = $(`#qtd_ad${advS}`).val();
    var id_produto = $('#id_produto').val();
    var id_carrinho = $('#id_carrinho').val();
    var id_empresa = $('#id_empresa').val();
    var id_customere = $('#id_customere').val();
    var valor = $(`#valor${advS}`).val();
    console.log(fieldName);
    $.ajax({
        url: `/${link_site}/product/addCarrinho/adicionais`,
        type: 'POST',
        data: {
            id_adicional: id_adicional,
            id_produto: id_produto,
            id_customere: id_customere,
            id_carrinho: id_carrinho,
            id_empresa: id_empresa,
            valor: valor,
            quantidade: quantidade
        },
        success: function(dd) {
            var valorAdicional = $(`#id_adicional${advS}`).attr('valor');
            var novoValor = (parseFloat(valorAdicional) + parseFloat(vbA));
            $('#total').text(formatter.format(novoValor));
            $('#valorFinal').val(novoValor);
            console.log(dd);
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
    $(".addStyle").show()
});

$('.addFavorito').click(function(e) {
    let id = $(this).attr('data-favorito');

    $.ajax({
        url: `/${link_site}/favorito/${id}`,
        type: 'get',
        data: {
            id: id
        },
        success: function(response) {
            //console.log(response)

            switch (response) {
                case 'Favorito Cadastrado':
                    $(`#itFavorito${id}`).removeClass('fa-heart-o')
                    $(`#itFavorito${id}`).addClass('fa-heart')
                    break;
                default:
                    $(`#itFavorito${id}`).removeClass('fa-heart')
                    $(`#itFavorito${id}`).addClass('fa-heart-o')
                    break;
            }
        },
        error: function(xhr) {}
    });
});


//Radio Buttom
$('.radioMoto input[type=radio]').on('change', function(e) {
    var status = $(this).val();

    if ($(this).is(":checked")) {
        if (status === "4") {
            $('.status4').addClass('border-primary')
            $('.status5').removeClass('border-primary')
            $('.recusaInfo').hide()
            $("#observacao").attr("required", "false");
            $("#fullBtnNac").text("INFORMAR AO RESTAURANTE");
        } else if (status === "5") {
            $('.status4').removeClass('border-primary')
            $('.status5').addClass('border-primary')
            $('.recusaInfo').show()
            $("#observacao").attr("required", "true");
            $("#fullBtnNac").text("INFORMAR RECUSA DO CLIENTE");
        }
    }
});


// This button will decrement the value till 0
$('[data-quantity="minus"]').click(function(e) {

    fieldName = $(this).attr('data-field');
    var currentVal = parseInt($('input[name=' + fieldName + ']').val());

    if (!isNaN(currentVal) && currentVal > 0) {
        $('input[name=' + fieldName + ']').val(currentVal - 1);
        var advS = $(this).attr('id-select');
        var vbA = $('#valorFinal').val();

    } else {
        $('input[name=' + fieldName + ']').val(0);
    }
    var currentVal = parseInt($('input[name=' + fieldName + ']').val());

    if (currentVal === 0) {
        $(`#id_adicional${advS}`).prop('checked', false);
        $(`.id_adicional${advS}`).hide();
        $(".addStyle").show();
        var chave = $('#chave').val();
        var id_carrinho = $('#id_carrinho').val();
        var id_empresa = $('#id_empresa').val();
        var id_customere = $('#id_customere').val();
        $.ajax({
            url: `/${link_site}/product/removeCarrinho/adicionalis/${chave}`,
            type: 'get',
            data: {
                chave: chave,
                id_carrinho: id_carrinho,
                id_adicional: advS,
                id_empresa: id_empresa,
                id_customere: id_customere
            },
            success: function(dd) {
                //location.reload();
                //console.log(dd)
                var valorAdicional = $(`#id_adicional${advS}`).attr('valor');
                var novoValor = (parseFloat(vbA) - parseFloat(valorAdicional));
                $('#total').text(formatter.format(novoValor));
                $('#valorFinal').val(novoValor);
            },
            error: function(xhr) {}
        });
    }

    var id_adicional = $(`#id_adicional${advS}`).val();
    var quantidade = $(`#qtd_ad${advS}`).val();
    var numero_pedido = $('#numero_pedido').val();
    var id_produto = $('#id_produto').val();
    var id_carrinho = $('#id_carrinho').val();
    var id_empresa = $('#id_empresa').val();
    var id_customere = $('#id_customere').val();
    var valor = $(`#valor${advS}`).val();

    $.ajax({
        url: `/${link_site}/product/addCarrinho/adicionais`,
        type: 'post',
        data: {
            id_adicional: id_adicional,
            id_produto: id_produto,
            id_carrinho: id_carrinho,
            id_customere: id_customere,
            id_empresa: id_empresa,
            valor: valor,
            quantidade: quantidade,
            numero_pedido: numero_pedido,
            chave: chave
        },
        success: function(dd) {
            //console.log(dd)
            var valorAdicional = $(`#id_adicional${advS}`).attr('valor');
            var novoValor = (parseFloat(vbA) - parseFloat(valorAdicional));
            $('#total').text(formatter.format(novoValor));
            $('#valorFinal').val(novoValor);
        },
        error: function(xhr) {}
    });

});


$('.quantity-left-minus, .minuss').prop("disabled", true);
$('.quantity-right-plus').click(function(e) {

    var a = $('#add_itens input[type=checkbox]:checked').length;
    var b = $('#add_itens input[type=radio]:checked').length;

    var adv = $('input[name="adicional[]"]:checked').attr('id'); //Get Id do item selecionado
    var vrec = $("#" + adv).attr('valor'); //Coloca em uma variavel e pega o valor em R$
    var vb = $('#valor').val(); // Valor do Produto

    var quantity = parseInt($('#quantity').val());
    var valor = parseFloat($('#valor').val());
    if (quantity === 1) {
        $('.quantity-left-minus').prop("disabled", true);
    }

    $('#quantity').val(quantity + 1);
    var quantityAtual = parseInt($('#quantity').val());
    $('#valorFinal').val(parseFloat(quantityAtual * valor))
    $('#total').text(formatter.format(quantityAtual * valor));

    $('.quantity-left-minus').prop("disabled", false);

    var qtd_i = $('#quantity').val();

    if (a || b !== 0) {

        $('#valor_bd').val(parseFloat(vc));
        if ($(this).is(":checked")) {
            var vc = parseFloat(qtd_i) * parseFloat(vb);
            $('#total').text(formatter.format(vc));
            $('#valorFinal').val(vc);
        } else {
            var vc = parseFloat(qtd_i) * parseFloat(vb);

            $('#total').text(formatter.format(vc));
            $('#valorFinal').val(vc);
        }

    }
});

$('.quantity-left-minus').click(function(e) {

    var a = $('#add_itens input[type=checkbox]:checked').length;
    var b = $('#add_itens input[type=radio]:checked').length;

    var adv = $('input[name="adicional[]"]:checked').attr('id'); //Get Id do item selecionado
    var vrec = $("#" + adv).attr('valor'); //Coloca em uma variavel e pega o valor em R$
    var vb = $('#valor').val(); // Valor do Produto

    var quantity = parseInt($('#quantity').val());
    var valor = parseFloat($('#valor').val());

    if (quantity > 1) {
        $('#quantity').val(quantity - 1);
        var quantityAtual = parseInt($('#quantity').val());
        $('#total').text(formatter.format(quantityAtual * valor));
        //console.log(quantityAtual * valor)
        $('#valorFinal').val(parseFloat(quantityAtual * valor))
    }
    if (parseInt($('#quantity').val()) === 1) {
        $('.quantity-left-minus').prop("disabled", true);
    } else {
        $('.quantity-left-minus').prop("disabled", false);
    }

    var qtd_i = $('#quantity').val();

    if (a || b !== 0) {
        $('#valor_bd').val(parseFloat(vc));
        if ($(this).is(":checked")) {

            var vc = parseFloat(qtd_i) * parseFloat(vb);
            $('#total').text(formatter.format(vc));
            $('#valorFinal').val(vc);
        } else {
            var vc = parseFloat(qtd_i) * parseFloat(vb);
            $('#total').text(formatter.format(vc));
            $('#valorFinal').val(vc);
        }

    }
});

/** 
 * Carrinho de compra e Formularios
 */

function mudarEndereco(id) {
    $.ajax({
        url: `/${link_site}/endereco/principal/${id}`,
        type: 'POST',
        data: id,
        beforeSend: function() {
            $(".btn_acao a, .btn_acao button").addClass('hide');
            $('.btn_acao .carrega').html('<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>');
        },
        complete: function() {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
        },
        success: function(data) {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
            console.log(data)
            switch (data.mensagem) {
                case 'Endereço definido como principal':
                    $('#mensagem').html(`<div class="alert alert-success" role="alert">${data.mensagem}</div>`);
                    window.location = `/${link_site}/enderecos`;
                    break;
                default:
                    $('#mensagem').html(`<div class="alert alert-danger" role="alert">${data.mensagem}</div>`);
                    break;
            }
        },
        error: function(data) {
            $('#mensagem').html(`<div class="alert alert-danger" role="alert">Opss tivemos um problema</div>`)
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', function() {}, false);
            }
            return myXhr;
        }
    });
    return false;
}
$("#form").submit(function(e) {
    var formData = new FormData(this);
    var idProd = $('#chave').val();
    var idCar = $('#id_carrinho').val();
    $.ajax({
        url: $('#form').attr('action'),
        type: 'POST',
        data: formData,
        beforeSend: function() {
            $(".btn_acao a, .btn_acao button").addClass('hide');
            $('.btn_acao .carrega').html('<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>');
        },
        success: function(dd) {
            console.log(dd);
            if (dd.id > 0) {
                console.log(dd.mensagem);
                switch (dd.mensagem) {
                    case 'Enviado o Código agora e só validar':
                        $('#ValidaCode').modal("show");
                        break;
                    case 'Aguarde estamos redirecionando para a pagina inicial':
                        $('#ValidaCode').modal("hide");
                        $('#mensagem').html(`<div class="alert alert-success" role="alert">${dd.mensagem}</div>`);
                        localStorage.setItem('username', $('#emailOurTel').val());
                        window.location = `/${link_site}/${dd.url}`;
                        break;
                    case 'Seu produto foi adicionado a Sacola!':
                        $('#FinalizarPedido').modal("show");
                        break;
                    case 'Pedido finalizado com sucesso':
                        $('#FinalizarPedidoOK').modal("show");
                        break;
                    case 'Seu produto foi adicionado a sacola aguarde que tem mais!':
                        $('#mensagem').html('Adicionamos seu item a sacola, aguarde que iremos redirecionar para o proximo passo!');
                        $('.successSup').show();
                        $('.errorSup, .buttonAlert').hide();
                        $('#alertGeralSite').modal("show");
                        window.location = `/${link_site}/${dd.url}/${dd.id}`;
                        break;
                    case 'OK Vai para os pedidos':
                        window.location = `/${link_site}/${dd.url}`;
                        break;
                    case 'Agradecemos pela sua avaliação!':
                        $('#mensagem').html(dd.mensagem);
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");
                        if (dd.url) {
                            $(".buttonAlert").on('click', function() {
                                window.location = `/${link_site}/${dd.url}`;
                            });
                        }
                        break;
                    case 'Seu cadastro foi realizado com sucesso':
                        $('#mensagem').html(dd.mensagem);
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");
                        if (dd.url) {
                            $(".buttonAlert").on('click', function() {
                                window.location = `/${link_site}/`;
                            });
                        }
                        break;
                    case 'Primeiro endereço cadastrado com Sucesso!':
                        $('#mensagem').html('Pronto! Agora que tenho suas informações de entrega revise os itens de seu carrinho, adicione ou remova itens para finalizar seu pedido');
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");
                        if (dd.url) {
                            $(".buttonAlert").on('click', function() {
                                window.location = `/${link_site}/${dd.url}`;
                            });
                        }
                        break;
                    case 'OK Vai para os carrinho':
                        window.location = `/${link_site}/${dd.url}`;
                        break;

                    case 'Enviamos em seu celular um código para validar seu acesso!':
                        $('#mensagem').html(dd.mensagem);
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");
                        if (dd.url) {
                            $(".buttonAlert").on('click', function() {
                                window.location = `/${link_site}/${dd.url}`;
                            });
                        }
                        break;
                    case 'Número Validado!':
                        $('#mensagem').html(dd.mensagem);
                        $('#duomensagem').html(dd.duomensagem);
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");

                        $('.buttonAlert').addClass("hide");
                        $('.buttonAlerts, .buttonAlertNop').removeClass("hide");

                        $(".buttonAlerts").on('click', function() {
                            window.location = `/${link_site}/${dd.url}`;
                        });
                        break;
                    case 'Pedido cancelado com sucesso!':
                        $('#cancelarPedido').modal("hide");
                        window.location = `/${link_site}/${dd.url}`;
                        break;
                    default:
                        $('#mensagem').html(dd.mensagem);
                        $('.successSup').show();
                        $('.errorSup').hide();
                        $('#alertGeralSite').modal("show");
                        if (dd.url) {
                            $(".buttonAlert").on('click', function() {
                                window.location = `/${link_site}/${dd.url}`;
                            });
                        }
                        break;
                }
            } else {
                console.log(dd.error);
                $(".btn_acao a, .btn_acao button").removeClass('hide');
                $('.btn_acao .carrega').html('')
                $('.errorSup').show();
                $('.successSup').hide();
                $('.modal-backdrop').remove();

                $('#alertGeralSite').modal("show");
                $('#mensagem').html(dd.error);
            }

        },
        error: function(dd) {
            $('#mensagem').html(`<div class="alert alert-danger" role="alert">Opss tivemos um problema</div>`)
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', function() {}, false);
            }
            return myXhr;
        }
    });
    return false;
});

$("#formFinish").submit(function() {
    var formData = new FormData(this);
    var idProd = $('#chave').val();
    var t_pg = $('#tipo_pagamento').val();
    var t_ft = $('#tipo_frete').val();
    if (t_ft == "") {
        $('#tipo_frete').shake();
        $('#acoes_carrinho').show();
        $('#carregando_carrinho').hide();
        exit();
    } else if (t_pg == "") {
        $('#alertGeralSite').modal('show');
        $('#mensagem').html('Faltou informar a Forma de Pagamento!');
        $('#acoes_carrinho').show();
        $('html,body').animate({ scrollTop: $("#t_pag").offset().top }, 'slow');
        $('#tipo_pagamento').shake().css("border", "1px solid red");
        $('#carregando_carrinho').hide();
    } else {
        $.ajax({
            url: $('#formFinish').attr('action'),
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $(".btn_acao a, .btn_acao button").addClass('hide');
                $('.btn_acao .carrega').html('<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>');
            },
            complete: function() {
                $('.btn_acao .carrega').html('')
            },
            success: function(data) {
                $(".btn_acao a, .btn_acao button").removeClass('hide');
                $('.btn_acao .carrega').html('')
                console.log(data);
                if (data.id > 0) {
                    $('#mensagem').html(data.mensagem);
                    $('#FinalizarPedidoOK').addClass('show');
                    $('#FinalizarPedidoOK').show();
                } else {
                    $('#mensagem').html(`<div class="alert alert-danger" role="alert">${data.error}</div>`);
                }
                $(".btnValida").prop("disabled", false);
                $('.btnValida').html('FINALIZAR PEDIDO');
            },
            error: function(data) {
                $(".btn_acao a, .btn_acao button").removeClass('hide');
                $('#mensagem').html(`<div class="alert alert-danger" role="alert">Opss tivemos um problema com seu pedido</div>`)
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function() {}, false);
                }
                return myXhr;
            }
        });
    }
    return false;
});

function produtosModal(id_produto, id_carrinho) {
    $.get(`/${link_site}/carrinho/pedido/acao/${id_produto}/${id_carrinho}`, function(dd) {
        let retorno = `
        <p id="mensagem" class="mb-4 text-center">Deseja remover este item <strong>${dd}</strong> do seu pedido?<p>
        <div class="mt-4 text-center">
            <a href="/${link_site}/carrinho/${id_produto}/d/${id_carrinho}" class="p-3 text-danger removerItem">REMOVER</a><br/><br/>
        </div>
        <div class="mt-3">
            <a href="#" class="mt-3 btn btn-primary btn-lg btn-block" data-dismiss="modal" aria-label="Close">Fechar e continuar</a>
        </div>`
        $('#retornoInf').html(retorno)
    })
}

/**
 * Atualizar dados para o Motoboy e Cliente em tempo real
 */
$("#formBusca").submit(function() {
    $.get(`/${link_site}/motoboy/pegar/entrega/busca`, function(dd) {
        var numero_pedido = $(`#numero_pedido`).val();
        var id_empresa = $(`#id_empresa`).val();
        var formData = {
            numero_pedido,
            id_empresa
        }
        if (parseInt(numero_pedido) === 0) {
            $('#mensagem').html(`<div class="alert alert-danger" role="alert">Para efetuar informe o numero do pedido</div>`)
        } else {
            $.ajax({
                url: `/${link_site}/motoboy/pegar/entrega/busca`,
                type: 'post',
                data: formData,
                beforeSend: function() {
                    $('.carregar').html(`<?xml version="1.0" encoding="utf-8"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><div class="mb-3 osahan-cart-item osahan-home-page"><div class="p-3 osahan-profile"><div class="osahan-text text-center mt-3"><p class="small mb-0">Estamos processando a sua busca aguarde um momento.</p></div></div></div>`);
                },
                complete: function(data) {
                    $('.carregar').html('');
                },
                success: function(data) {
                    console.log(data);
                    $('#pesquisaEntregasMotoboy').html(data);
                },
                error: function(data) {
                    $('.carregar').html(`<div class="mb-3 osahan-cart-item osahan-home-page"><div class="p-3 osahan-profile"><div class="osahan-text text-center mt-3"><h4 class="text-primary">Nenhum pedido foi encontrado.</h4><p class="small mb-0">Verifique o número digitado ou tente novamente.</p></div></div></div>`);
                }
            });
        }
        $('#pesquisaEntregasMotoboy').html(dd);
        $('#mensagem').html('')
    })
    return false;
});

function mudarStatusEntrega(id, status, id_caixa, id_motoboy, numero_pedido, id_empresa, id_customere) {

    let valores = {
            id,
            status,
            id_caixa,
            id_motoboy,
            numero_pedido,
            id_empresa,
            id_customere
        }
        //console.log(valores);
    $.ajax({
        url: `/${link_site}/admin/pedido/mudar/${id}/${status}/${id_caixa}/${id_motoboy}`,
        method: "post",
        data: valores,
        dataType: "text",
        success: function(dd) {
            if (dd.mensagem == 'Status alterado com sucesso') {
                $('#mensagem').html("Legal! Agora e só iniciar a entrega deste pedido!");
                $('.successSup').show();
                $('.errorSup').hide();
                $('#alertGeralSite').modal("show");
                $(".buttonAlert").on('click', function() {
                    window.location = `/${link_site}/motoboy/entregas`;
                });
            } else {
                $('#mensagem').html("Vixi! Algum problema aconteceu. Tente Novamente");
                $('.successSup').hide();
                $('.errorSup').show();
                $('#alertGeralSite').modal("show");
            }
        },
    })

}

$(document).ready(function() {
    if ($(document).find(`[data-tipo_escolha='2']`).length > 0) {
        $(`.addStyleMod, .addStyleModT, .addStyle`).hide();
    }
    $('#add_itenSabores').each(function() {
        if (parseInt($(`#add_itenSabores`).length) > 0) {
            $(`.addStyle`).hide();
        }
    });
    $('[data-tipo]').each(function(i, ele) {
        let verifica = $(`#${ele['id']} .custom-control`).length
        if (parseInt(verifica) === 0) {
            $(`#${ele['id']}`).remove();
        }
    });

    function atualizarMes() {
        $('.carregar').html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path>');
        $.get(`/${link_site}/motoboy/buscar/entregas/mes`, function(dd) {
            $('#dadosMes').html(dd);
            $('.carregar').html('');
        })
    }
    atualizarMes();

    function atualizar() {
        $.get(`/${link_site}/home/ultimaVenda`, function(dd) {
            if (parseInt($(dd).attr('data-status')) === 1) {
                localStorage.setItem("sttInit", $(dd).attr('data-status'));
            }
            if (parseInt($(dd).attr('data-status')) > parseInt(localStorage.getItem("sttInit"))) {
                localStorage.setItem("sttInit", $(dd).attr('data-status'));
                var audio = new Audio('/audio/gS21.mp3');
                audio.play();
            }

            //console.log(dd)
            $('#listarUltimaVenda').html(dd);
        })

        let dataPedido = $('#acompanharStatusPedido').attr('data-pedido')
        if (dataPedido != undefined) {
            $.get(`/${link_site}/meu-pedido/acompanharStatusPedido/${dataPedido}`, function(dd) {
                console.log('oi')
                if (parseInt($(dd).attr('data-status')) === 1) {
                    localStorage.setItem("sttInit", $(dd).attr('data-status'));
                }
                if (parseInt($(dd).attr('data-status')) > parseInt(localStorage.getItem("sttInit"))) {
                    localStorage.setItem("sttInit", $(dd).attr('data-status'));
                    var audio = new Audio('/audio/gS21.mp3');
                    audio.play();
                }
                $('#acompanharStatusPedido').html(dd);
            })
        }
        $.get(`/${link_site}/motoboy/pedido/listar`, function(dd) {
            $('#listarEntregasMotoboy').html(dd);
        })

        $.get(`/${link_site}/motoboy/pedido/listar/qtd`, function(dd) {
            if (parseInt($(dd).text()) === 0) {
                localStorage.setItem("quantidadeInicial", $(dd).text());
            }
            if (parseInt($(dd).text()) > parseInt(localStorage.getItem("quantidadeInicial"))) {
                localStorage.setItem("quantidadeInicial", $(dd).text());
                var audio = new Audio('/audio/gS21.mp3');
                audio.play();
            }
            $('#mostrarQtd').html(dd);
        })

        if (localStorage.getItem("u") && localStorage.getItem("n")) {

            let u = localStorage.getItem("u");
            let n = localStorage.getItem("n");
            let valores = {
                u,
                n
            }
            if (localStorage.getItem("u") !== null) {
                $.ajax({
                    url: `/${link_site}/u/l/val`,
                    method: "POST",
                    data: valores,
                    dataType: "text",
                    success: function(dd) {
                        //console.log(dd);
                    },
                })
            }
        } else {
            $.get(`/${link_site}/u/valid`, function(dd) {
                console.log(dd);
                if (dd.user !== null && dd.user !== null) {
                    localStorage.setItem('u', dd.user);
                    localStorage.setItem('n', dd.nivel);
                }

            })
        }
    }
    setInterval(atualizar, 10000);
    $(function() {
        atualizar();
    });

    var quantityAtual = $('#quantity').val();
    if (parseInt(quantityAtual) > 0) {
        $('.quantity-left-minus').prop("disabled", false);
    } else {
        $('.quantity-left-minus').prop("disabled", true);
    }
});

$('.adicional_item_ok').hide();

window.onscroll = function() {
    myFunction()
};
var header = document.getElementById("menuCat");
if (header !== null) {
    var sticky = header.offsetTop;
}

function myFunction() {
    if (window.pageYOffset > sticky) {
        if (header !== null) {
            header.classList.add("sticky");
        }
    } else {
        if (header !== null) {
            header.classList.remove("sticky");
        }
    }
}

$('.smoothScroll').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        let verifica = $(this).attr('id')
        if (verifica === verifica) {
            $('.smoothScroll').removeClass('active')
            $(`#${verifica}`).addClass('active')
        }

        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top - 110
            }, 1000);
            return false;
        }
    }
});

$("#mes").change(function() {
    var mes = $(this).val();
    console.log(mes)
    var formData = {
        mes
    }
    $.ajax({
        url: `/${link_site}/motoboy/buscar/entregas/mes`,
        type: 'post',
        data: formData,
        beforeSend: function() {
            $('.carregar').html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path>');
        },
        complete: function(data) {
            $('.carregar').html('');
        },
        success: function(dd) {
            console.log(dd)
            $('#dadosMes').html(dd);
        }
    });
})

$("#button-addon2").on('click', function() {
    var cupomDesconto = $('#cupomDesconto').val();
    var formData = {
        'cupomDesconto': cupomDesconto
    }
    $.ajax({
        url: `/${link_site}/cupomDesconto`,
        type: 'POST',
        data: formData,
        beforeSend: function() {
            $('.button-addon2').html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path>');
        },
        success: function(dd) {
            //console.log(dd);
            let retorno = parseFloat(dd.id)
            if (retorno === 0) {
                $('.mensagemID').html(dd.mensagem);
                $('.successSup').hide();
                $('.errorSup').show();
                $('#alertGeralSite').modal("show");
                //console.log(dd);
            } else {
                $('#itemCupom, .errorSup').hide();
                $('#cupomCal, .successSup').show();
                $('#cupomCal span').text('- ' + formatter.format(retorno))

                let total_pago = $('#total_pago').val();
                let novoValor = parseFloat(total_pago) - retorno

                $('#valorProdutoMostra').text(formatter.format(novoValor))
                $('#pagCartao, #total_pago').val(novoValor)

                $('.mensagemID').html("Cupom aplicado com sucesso!");
                $('#alertGeralSite').modal("show");

                //console.log(retorno)
            }

            //$('#cupomDesconto').html(dd);


        }
    });
});

var foraDaAreaEntrega = $('#foraDaAreaEntrega').length;
if (foraDaAreaEntrega > 0) {
    $('.mensagemID').html("O seu endereço esta fora da área de Entrega e infelizmente este estabelecimento não possui retirada no local! </br> Mudar <a href='/enderecos'>Endereço de entrega</a>");
    $('.successSup').hide();
    $('.swal2-actions').hide();
    $('.errorSup').show();
    $('#alertGeralSite').modal("show");
}

$("#btnValidarCode").on('click', function() {
    let telefone = $("#telefone").val();
    let id_empresa = $("#id_empresa").val();
    let codeValida = $("#codeValida").val();
    let valores = {
        telefone,
        id_empresa,
        codeValida
    }
    $.ajax({
        url: `/${link_site}/carrinho/valida/acesso/code`,
        method: "POST",
        data: valores,
        dataType: "text",
        beforeSend: function() {
            $(".btn_acao a, .btn_acao button").addClass('hide');
            $('.btn_acao .carrega').html('<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>');
        },
        complete: function() {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
        },
        success: function(dd) {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
            console.log(dd)
            switch (dd.mensagem) {
                case 'OK Vai para o carrinho':
                    $('#ValidaCode').modal("hide");
                    window.location = `/${link_site}/carrinho`;
                    break;
                default:
                    $('#ValidaCode').modal("hide");
                    $('#mensagem').html(dd.mensagem);
                    $('.successSup').hide();
                    $('.errorSup').show();
                    $('#alertGeralSite').modal("show");
                    break;
            }
        },
    })
});


$("#btnValidarCodeLogin").on('click', function() {
    let telefone = $("#emailOurTel").val();
    let id_empresa = $("#id_empresa").val();
    let codeValida = $("#codeValida").val();
    let valores = {
        telefone,
        id_empresa,
        codeValida
    }
    $.ajax({
        url: `/${link_site}/valida/acesso/code`,
        method: "POST",
        data: valores,
        dataType: "text",
        beforeSend: function() {
            $(".btn_acao a, .btn_acao button").addClass('hide');
            $('.btn_acao .carrega').html('<div class="env"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="30px" height="30px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><path d="M10 50A40 40 0 0 0 90 50A40 42 0 0 1 10 50" fill="#a90e19" stroke="none"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 51;360 50 51"></animateTransform></path><br/>Aguarde processando informações!</div>');
        },
        complete: function() {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
        },
        success: function(dd) {
            $(".btn_acao a, .btn_acao button").removeClass('hide');
            $('.btn_acao .carrega').html('')
            console.log(dd)
            switch (dd.mensagem) {
                case 'Aguarde estamos redirecionando para a pagina inicial':
                    $('#ValidaCode').modal("hide");
                    localStorage.setItem('username', $('#emailOurTel').val());
                    window.location = `/${link_site}/login/n`;
                    break;
                default:
                    $('#ValidaCode').modal("hide");
                    $('#mensagem').html(dd);
                    $('.successSup').hide();
                    $('.errorSup').show();
                    $('#alertGeralSite').modal("show");
                    break;
            }
        },
    })
});

$('.abreelemento').show();

function produtoModalView(id_produto) {
    $('#myitemsModal').modal("show");
    $.ajax({
        url: `/${link_site}/pedido/desk/mostrar/${id_produto}`,
        method: "post",
        data: {
            id_produto
        },
        dataType: "html",
        success: function(dd) {
            $('#productTitle').html(dd.titulo)
        },
    })
}

$("#telefoneVeri").on('blur touchleave touchcancel', function() {
    let telefone = $(this).val();
    $.ajax({
        url: `/${link_site}/verifica/telefone`,
        method: "post",
        data: {
            telefone
        },
        dataType: "html",
        success: function(dd) {
            console.log(dd)
            if (parseInt(dd) === 1) {
                $('#ValidaCode').modal("hide");
                $('#mensagem').html("Telefone já cadastrado! Faça o login ou tente outro número");
                $('.successSup').hide();
                $('.errorSup').show();
                $('#alertGeralSite').modal("show");
            }
        },
    })
})

$("#numero").on('blur touchleave touchcancel mouseout click', function() {
    var rua = $("#rua").val().replace(" ", "%20");
    var numero = $("#numero").val().replace(" ", "%20");
    var cidade = $("#cidade").val().replace(" ", "%20");
    var estado = $("#estado").val().replace(" ", "%20");
    var url = `https://maps.google.com/maps/api/geocode/json?address=${rua}%20${numero}%20-%20${cidade}&sensor=false&key=AIzaSyAxhvl-7RHUThhX4dNvCGEkPuOoT6qbuDQ`;

    $.getJSON(url, function(place) {
        //console.log(place.results[0].address_components)
        for (const component of place.results[0].address_components) {
            const componentType = component.types[0];
            console.log(component)
            switch (componentType) {
                case "street_number":
                    {
                        $("#numero").val(component.long_name);
                        break;
                    }
                case "route":
                    {
                        $("#rua").val(component.long_name);
                        break;
                    }
                case "postal_code":
                    {
                        $("#cep").val(component.long_name);
                        break;
                    }
                case "administrative_area_level_2":
                    {
                        $("#cidade").val(component.long_name);
                        break;
                    }
                case "administrative_area_level_1":
                    {
                        $("#estado").val(component.short_name);
                        break;
                    }
                case "sublocality":
                    $("#bairro").val(component.short_name);
                    break;
                case "political":
                    $("#bairro").val(component.short_name);
                    break;
                case "sublocality_level_1":
                    $("#bairro").val(component.short_name);
                    break;
            }
        }

        // try {
        //     // Preenche os campos de acordo com o retorno da pesquisa
        //     $("#endereco").val(dadosRetorno.logradouro);
        //     $("#bairro").val(dadosRetorno.bairro);
        //     $("#cidade").val(dadosRetorno.localidade);
        //     $("#uf").val(dadosRetorno.uf);
        // } catch (ex) {}
    });
})