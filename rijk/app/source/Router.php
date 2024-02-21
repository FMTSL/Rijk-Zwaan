<?php
$route = new \CoffeeCode\Router\Router(ROOT);

/**
 * APP
 */
$route->namespace("Source\App");

/**
 * Admin
 */
$route->group(null);
//$route->get("/", "Web:home");
$route->get("/", "AppOrderForm:listViewNew");

/**
 * Authentication
 */
$route->get("/login", "Auth:login");
$route->post("/auth/login", "Auth:authLogin");
$route->get("/registration", "Auth:cadastro");
$route->post("/auth/registration", "Auth:authCadastro");
$route->get("/recover-password", "Auth:recuperarSenha");
$route->post("/auth/recover-password", "Auth:authRecuperarSenha");
$route->get("/logout", "Auth:logout");

/**
 * Cruds Strutural Pedidos
 */
$route->get("/orders", "AppOrderForm:listView");
$route->get("/orders/lists/all", "AppOrderForm:listViewAll");
$route->get("/unfinished-orders", "AppOrderForm:listViewUnfinished");
$route->get("/unfinished-orders/lists/all", "AppOrderForm:listViewAllUnfinished");
$route->delete("/unfinished-orders/delete", "AppOrderForm:deleteUnfinishedAction");
//Retorna os produtos para o estoque
$route->get("/return-products", "AppOrderForm:returnProducts");


$route->get("/client-service", "AppOrderClientServiceForm:listView");
$route->get("/client-service/lists/all", "AppOrderClientServiceForm:listViewAll");

$route->get("/order-to-orders/client-service/logistics/{id}/{clientid}/{pedido}", "AppOrderClientServiceForm:listViewOrder");

$route->get("/order-to-orders/client-service/logistics/{id}/{clientid}/{pedido}/pending", "AppOrderClientServiceForm:listViewOrderPending");


$route->get("/order-to-orders/client-service/logistics/pdf/{id}/{clientid}/{pedido}", "AppOrderClientServiceForm:listViewOrderPrint");
$route->post("/order-to-order/client-service/logistic/{id}", "AppOrderClientServiceForm:updateAction");

//Inicia Pedido
$route->get("/order-to-order", "AppOrderForm:listViewNew");
//Pagina Pedido
$route->get("/order-to-order/{id}/{clientid}/{pedido}", "AppOrderForm:listViewNewUpdate");
//Step 01
$route->get("/order-to-order/{id}/{clientid}/{pedido}/step-one", "AppOrderForm:stepOne");
$route->post("/order/form/step-one/update", "AppOrderForm:stepOneUpdate");

//Step 02
$route->get("/order-to-order/{id}/{clientid}/{pedido}/step-two", "AppOrderForm:stepTwo");
$route->get("/order-to-order-list/{id}/{clientid}/step-two-products", "AppOrderForm:stepTwoProducts");
$route->post("/order-to-order/carrinho/new", "AppOrderForm:newCartAction");
//$route->post("/order/form/step-one/update", "AppOrderForm:stepOneUpdate");

//Step 03
$route->get("/order-to-order/{id}/{clientid}/{pedido}/step-tree", "AppOrderForm:stepTree");
$route->get("/order-to-order-list/{id}/{clientid}/step-tree-products", "AppOrderForm:stepTreeProducts");
$route->post("/order-to-order/carrinho/new-tree", "AppOrderForm:newCartActionTree");

//Step 04
$route->get("/order-to-order/{id}/{clientid}/{pedido}/step-four", "AppOrderForm:stepFour");
$route->get("/order-to-order-list/{id}/{clientid}/step-four-products", "AppOrderForm:stepFourProducts");
$route->post("/order-to-order/carrinho/new-four", "AppOrderForm:newCartActionFour");

//Step 05
$route->get("/order-to-order/{id}/{clientid}/{pedido}/step-six", "AppOrderForm:stepSix");
$route->get("/order-to-order-list/{id}/{clientid}/step-six-products", "AppOrderForm:stepSixProducts");
$route->post("/order-to-order/carrinho/new-six", "AppOrderForm:newCartActionSix");

$route->get("/order-to-order-total/{id}/{clientid}/total", "AppOrderForm:listViewNewUpdateValorTotal");

$route->get("/order/{id}/{clientid}/{pedido}", "AppOrderForm:listViewOrder");
$route->get("/order-to-order/step-two/{clientid}/{pedido}", "AppOrderForm:listViewNewTwo");
$route->get("/order-to-order/step-tree/{clientid}/{pedido}", "AppOrderForm:listViewNewTree");

$route->post("/order-to-order/order-payment-type", "AppOrderForm:updatePaymentType");
$route->post("/order-to-order/new", "AppOrderForm:newAction");

$route->post("/order-to-order/form/new-step-one", "AppOrderForm:newOrderAction");
$route->post("/order-to-order/form/new-step-tree", "AppOrderForm:newOrderTreeAction");
$route->post("/order-to-order/form/new-finish", "AppOrderForm:newOrderFourAction");

$route->get("/order-to-order/{id}", "AppOrderForm:updateView");
$route->post("/order-to-order/update", "AppOrderForm:updateAction");
$route->delete("/order-to-order/delete", "AppOrderForm:deleteAction");





$route->get("/order-to-order/freight-calculation/{id}/{weight}/{idOrder}", "AppOrderForm:freightCalculation");
$route->get("/order-to-order/freight-calculation/{id}/{weight}/{idOrder}/retirada", "AppOrderForm:freightCalculationRetirada");

$route->get("/order-to-order/client/{id}", "AppOrderForm:getClientInfo");
$route->get("/order-to-order/product/{id}", "AppOrderForm:getProductInfo");
$route->get("/order-to-order/products/{id}", "AppOrderForm:getListProducts");
$route->get("/order-to-order/products/total/{id}", "AppOrderForm:getPriceOrder");
//$route->get("/order-to-order/products/{id}", "AppOrderForm:getProductOrderCart");

$route->get("/order-to-orders/logistics", "AppLogistic:listView");
$route->get("/order-to-orders/logistics/lists/all", "AppLogistic:listViewAll");
$route->get("/order-to-orders/logistics/{id}/{clientid}/{pedido}", "AppLogistic:listViewOrder");
$route->get("/order-to-orders/logistics/pdf/{id}/{clientid}/{pedido}", "AppLogistic:listViewOrderPrint");
$route->get("/order-to-orders/logistics/pdf-generate/{id}/{clientid}/{pedido}", "AppLogistic:listViewOrderPrintPDF");
$route->post("/order-to-order/logistic/{id}", "AppLogistic:updateAction");
$route->post("/order-to-order/logistic-quotation/{id}", "AppLogistic:updateActionQuotation");






$route->get("/order/{id}", "AppOrderForm:getProductOrder");
$route->get("/teste", "AppOrderForm:teste");


/**
 * Cruds
 */
$route->get("/products", "AppProducts:listView");
$route->post("/product/new", "AppProducts:newAction");
$route->get("/product/{id}", "AppProducts:updateView");

$route->get("/products/lists/all", "AppProducts:listViewAll");
$route->post("/product/update", "AppProducts:updateAction");
$route->delete("/product/delete", "AppProducts:deleteAction");

$route->get("/request-status", "AppAssistStatus:listView");
$route->get("/request-status/lists/all", "AppAssistStatus:listViewAll");
$route->post("/request-status/new", "AppAssistStatus:newAction");
$route->get("/request-status/{id}", "AppAssistStatus:updateView");
$route->post("/request-status/update", "AppAssistStatus:updateAction");
$route->delete("/request-status/delete", "AppAssistStatus:deleteAction");

$route->get("/assists-tax-rates", "AppAssistTaxRates:listView");
$route->get("/assisst-tax-rates/lists/all", "AppAssistTaxRates:listViewAll");
$route->post("/assist-tax-rates/new", "AppAssistTaxRates:newAction");
$route->get("/assist-tax-rates/{id}", "AppAssistTaxRates:updateView");
$route->post("/assist-tax-rates/update", "AppAssistTaxRates:updateAction");
$route->delete("/assist-tax-rates/delete", "AppAssistTaxRates:deleteAction");




$route->get("/discounts", "AppDiscount:listView");
$route->get("/discount/lists/all", "AppDiscount:listViewAll");
$route->post("/discount/new", "AppDiscount:newAction");
$route->get("/discount/{id}", "AppDiscount:updateView");
$route->get("/discount/list/{id}/{idClient}", "AppDiscount:listViewId");
$route->post("/discount/update", "AppDiscount:updateAction");
$route->delete("/discount/delete", "AppDiscount:deleteAction");

$route->get("/product/stock", "AppProductsStock:listView");
$route->get("/product/stock/lists/all", "AppProductsStock:listViewAll");
$route->get("/product/list/{id}", "AppProductsStock:listViewId");
$route->post("/product/stock/new", "AppProductsStock:newAction");
$route->get("/product/stock/{id}", "AppProductsStock:updateView");
$route->post("/product/stock/update", "AppProductsStock:updateAction");
$route->delete("/product/stock/delete", "AppProductsStock:deleteAction");


$route->get("/product/stock/clone", "AppProductsStockClone:listView");
$route->get("/product/stock/clone/lists/all", "AppProductsStockClone:listViewAll");
$route->get("/product/list/clone/{id}", "AppProductsStockClone:listViewId");
$route->post("/product/stock/clone/new", "AppProductsStockClone:newAction");
$route->get("/product/stock/clone/{id}", "AppProductsStockClone:updateView");
$route->post("/product/stock/clone/update", "AppProductsStockClone:updateAction");
$route->delete("/product/stock/clone/delete", "AppProductsStockClone:deleteAction");
$route->delete("/product/stock/clone/delete/all", "AppProductsStockClone:deleteAllAction");


$route->get("/bonus-order", "AppBonusOrder:listView");
$route->get("/bonus-order/lists/all", "AppBonusOrder:listViewAll");
$route->post("/bonus/new", "AppBonusOrder:newAction");
$route->get("/bonus/{id}", "AppBonusOrder:updateView");
$route->post("/bonus/update", "AppBonusOrder:updateAction");
$route->delete("/bonus/delete", "AppBonusOrder:deleteAction");

$route->get("/salanovas", "AppSalanova:listView");
$route->get("/salanovas/lists/all", "AppSalanova:listViewAll");
$route->post("/salanova/new", "AppSalanova:newAction");
$route->get("/salanova/{id}", "AppSalanova:updateView");
$route->post("/salanova/update", "AppSalanova:updateAction");
$route->delete("/salanova/delete", "AppSalanova:deleteAction");

$route->get("/products-crop", "AppProductsCrop:listView");
$route->get("/products-crop/lists/all", "AppProductsCrop:listViewAll");
$route->post("/product-crop/new", "AppProductsCrop:newAction");
$route->get("/product-crop/{id}", "AppProductsCrop:updateView");
$route->post("/product-crop/update", "AppProductsCrop:updateAction");
$route->delete("/product-crop/delete", "AppProductsCrop:deleteAction");


$route->get("/calibres", "AppProductsCalibre:listView");
$route->post("/calibre/new", "AppProductsCalibre:newAction");
$route->get("/calibre/{id}", "AppProductsCalibre:updateView");
$route->get("/calibres/lists/all", "AppProductsCalibre:listViewAll");
$route->post("/calibre/update", "AppProductsCalibre:updateAction");
$route->delete("/calibre/delete", "AppProductsCalibre:deleteAction");

$route->get("/users", "AppUser:listView");
$route->post("/user/new", "AppUser:newAction");
$route->get("/user/{id}", "AppUser:updateView");
$route->post("/user/update", "AppUser:updateAction");
$route->get("/users/lists/all", "AppUser:listViewAll");
$route->delete("/user/delete", "AppUser:deleteAction");

$route->get("/salesman", "AppSalesman:listView");
$route->post("/salesman/new", "AppSalesman:newAction");
$route->post("/user/salesman/new", "AppSalesman:newUserAction");
$route->get("/salesman/{id}", "AppSalesman:updateView");
$route->get("/salesman/lists/all", "AppSalesman:listViewAll");
$route->post("/salesman/update", "AppSalesman:updateAction");
$route->delete("/salesman/delete", "AppSalesman:deleteAction");

$route->get("/customers", "AppCustomers:listView");
$route->get("/customers/lists/all", "AppCustomers:listViewAll");
$route->post("/customer/new", "AppCustomers:newAction");
$route->get("/customer/{id}", "AppCustomers:updateView");
$route->get("/get/customer/{id}", "AppCustomers:getView");
$route->post("/customer/update", "AppCustomers:updateAction");
$route->delete("/customer/delete", "AppCustomers:deleteAction");

$route->get("/customers-address", "AppAddress:listView");
$route->get("/customers-address/lists/all", "AppAddress:listViewAll");
$route->post("/customer-address/new", "AppAddress:newAction");
$route->get("/customer-address/{id}", "AppAddress:updateView");
$route->post("/customer-address/update", "AppAddress:updateAction");
$route->delete("/customer-address/delete", "AppAddress:deleteAction");

$route->get("/customer-category", "AppCustomerCategory:listView");
$route->get("/customer-category/lists/all", "AppCustomerCategory:listViewAll");
$route->get("/customer-category/{id}", "AppCustomerCategory:updateView");
$route->post("/customer-category/new", "AppCustomerCategory:newAction");
$route->delete("/customer-category/delete", "AppCustomerCategory:deleteAction");


$route->get("/customer-type", "AppCustomerType:listView");
$route->get("/customer-type/lists/all", "AppCustomerType:listViewAll");
$route->post("/customer-type/new", "AppCustomerType:newAction");
$route->get("/customer-type/{id}", "AppCustomerType:updateView");
$route->post("/customer-type/update", "AppCustomerType:updateAction");
$route->delete("/customer-type/delete", "AppCustomerType:deleteAction");

$route->get("/varieties", "AppProductsVariety:listView");
$route->get("/varieties/lists/all", "AppProductsVariety:listViewAll");
$route->post("/variety/new", "AppProductsVariety:newAction");
$route->get("/variety/{id}", "AppProductsVariety:updateView");
$route->get("/varieties/list/{id_variety}", "AppProductsVariety:listViewId");
$route->post("/variety/update", "AppProductsVariety:updateAction");
$route->delete("/variety/delete", "AppProductsVariety:deleteAction");


$route->get("/chemical-treatment", "AppProductsChemicalTreatment:listView");
$route->get("/chemical-treatment/lists/all", "AppProductsChemicalTreatment:listViewAll");
$route->post("/chemical-treatment/new", "AppProductsChemicalTreatment:newAction");
$route->get("/chemical-treatment/{id}", "AppProductsChemicalTreatment:updateView");
$route->post("/chemical-treatment/update", "AppProductsChemicalTreatment:updateAction");
$route->delete("/chemical-treatment/delete", "AppProductsChemicalTreatment:deleteAction");

$route->get("/sales-unit", "AppProductsSalesUnit:listView");
$route->get("/sales-unit/lists/all", "AppProductsSalesUnit:listViewAll");
$route->post("/sales-unit/new", "AppProductsSalesUnit:newAction");
$route->get("/sales-unit/{id}", "AppProductsSalesUnit:updateView");
$route->post("/sales-unit/update", "AppProductsSalesUnit:updateAction");
$route->delete("/sales-unit/delete", "AppProductsSalesUnit:deleteAction");

$route->get("/packaging", "AppProductsPackaging:listView");
$route->get("/packaging/lists/all", "AppProductsPackaging:listViewAll");
$route->post("/packing/new", "AppProductsPackaging:newAction");
$route->get("/packing/{id}", "AppProductsPackaging:updateView");
$route->post("/packing/update", "AppProductsPackaging:updateAction");
$route->delete("/packing/delete", "AppProductsPackaging:deleteAction");

$route->get("/customer-categories", "AppCustomerCategory:listView");
$route->get("/customer-categories/lists/all", "AppCustomerCategory:listViewAll");
$route->post("/customer-category/new", "AppCustomerCategory:newAction");
$route->get("/customer-category/{id}", "AppCustomerCategory:updateView");
$route->post("/customer-category/update", "AppCustomerCategory:updateAction");
$route->delete("/customer-category/delete", "AppCustomerCategory:deleteAction");

$route->get("/pib/customer-category/{id}", "AppCustomers:listViewDeadline");
$route->get("/pib/customer-category/lists/all", "AppCustomers:listViewAll");
$route->post("/pib/customer-category/new", "AppCustomers:newActionDeadline");
$route->delete("/pib/customer-category/delete", "AppCustomers:deleteActionDeadline");

$route->get("/customer-category-deadline/{id}", "AppCustomerCategory:listViewDeadline");
$route->get("/customer-category-deadline/lists/all", "AppCustomerCategory:listViewAll");
$route->post("/customer-category-deadline/new", "AppCustomerCategory:newActionDeadline");
$route->delete("/customer-category-deadline/delete", "AppCustomerCategory:deleteActionDeadline");


$route->get("/credit-term", "AppCustomerCreditDeadline:listView");
$route->get("/credit-term/lists/all", "AppCustomerCreditDeadline:listViewAll");
$route->post("/credit-term/new", "AppCustomerCreditDeadline:newAction");
$route->get("/credit-term/{id}", "AppCustomerCreditDeadline:updateView");
$route->get("/credit-term/list/{id_customer_category}/{payment_type}", "AppCustomerCreditDeadline:listViewId");
$route->post("/credit-term/update", "AppCustomerCreditDeadline:updateAction");
$route->delete("/credit-term/delete", "AppCustomerCreditDeadline:deleteAction");

$route->get("/payment-type", "AppCustomerPaymentTerm:listView");
$route->get("/payment-type/lists/all", "AppCustomerPaymentTerm:listViewAll");
$route->post("/payment-type/new", "AppCustomerPaymentTerm:newAction");
$route->get("/payment-type/{id}", "AppCustomerPaymentTerm:updateView");
$route->post("/payment-type/update", "AppCustomerPaymentTerm:updateAction");
$route->delete("/payment-type/delete", "AppCustomerPaymentTerm:deleteAction");

$route->get("/export/products", "AppExportsProductsStock:newAction");
$route->get("/import/products", "AppImportsProductsStock:listView");
$route->post("/import/products/new", "AppImportsProductsStock:newAction");

$route->get("/export/products/clone", "AppExportsProductsStockClone:newAction");
$route->get("/import/products/clone", "AppImportsProductsClone:listView");
$route->post("/import/products/new/clone", "AppImportsProductsClone:newAction");


$route->get("/import/customers", "AppImportsCustomers:listView");
$route->post("/import/client/new", "AppImportsCustomers:newAction");

$route->get("/import/products", "AppImportsProducts:listView");
$route->post("/import/products/new", "AppImportsProducts:newAction");


$route->get("/files", "AppFiles:listView");
$route->get("/files/lists/all", "AppFiles:listViewAll");
$route->get("/files/new", "AppFiles:listViewNew");
$route->post("/files/new-action", "AppFiles:newAction");
$route->delete("/files/delete", "AppFiles:deleteAction");



/**
 * ERROR
 */
$route->group("ops");
$route->get("/{errcode}", "Web:error");

/**
 * PROCESS
 */
$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}