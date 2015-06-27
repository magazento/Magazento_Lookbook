Product.Config.prototype.fillSelect = function(element){
    
        var attributeId = element.id.replace(/[a-z]*_[0-9]*_|[a-z]*/, '');
       //  var attributeId = element.id.replace(/[a-z]*/, '');
        var productId = element.id.match(/[a-z]*_([0-9]*)_[0-9]*/, '');
       productId=productId[1];
        var options = this.getAttributeOptions(attributeId,element);
        this.clearSelect(element);
        element.options[0] = new Option(this.config.chooseText, '');

        var prevConfig = false;
        if(element.prevSetting){
            prevConfig = element.prevSetting.options[element.prevSetting.selectedIndex];
        }

        if(options) {
            var index = 1;
            for(var i=0;i<options.length;i++){
                var allowedProducts = [];
                if(prevConfig) {
                    for(var j=0;j<options[i].products.length;j++){
                        if(prevConfig.config.allowedProducts
                            && prevConfig.config.allowedProducts.indexOf(options[i].products[j])>-1){
                            allowedProducts.push(options[i].products[j]);
                        }
                    }
                } else {
                    allowedProducts = options[i].products.clone();
                }

                if(allowedProducts.size()>0){
                    options[i].allowedProducts = allowedProducts;
                    element.options[index] = new Option(this.getOptionLabel(options[i], options[i].price), options[i].id);
                    if (typeof options[i].price != 'undefined') {
                        element.options[index].setAttribute('price', options[i].price);
                    }
                    element.options[index].config = options[i];
                    index++;
                }
            }
        }
   
};
Product.Config.prototype.initialize = function(config){
   // this.config     = new Array();
    this.config =  config;
   // this.settings = new Array();
        this.taxConfig  = this.config.taxConfig;
        if (config.containerId) {
            this.settings   = $$('#' + config.containerId + ' ' + '.super-attribute-select');
        } else {
            var classname = '.super-attribute-select-'+config.productId;
            this.settings   = $$(classname);
        }
        this.state      = new Hash();
        this.priceTemplate = new Template(this.config.template);
        this.prices     = config.prices;
        
        // Set default values from config
        if (config.defaultValues) {
            this.values = config.defaultValues;
        }
        
        // Overwrite defaults by url
        var separatorIndex = window.location.href.indexOf('#');
        if (separatorIndex != -1) {
            var paramsStr = window.location.href.substr(separatorIndex+1);
            var urlValues = paramsStr.toQueryParams();
            if (!this.values) {
                this.values = {};
            }
            for (var i in urlValues) {
                this.values[i] = urlValues[i];
            }
        }
        
        // Overwrite defaults by inputs values if needed
        if (config.inputsInitialized) {
            this.values = {};
            this.settings.each(function(element) {
                if (element.value) {
                    var attributeId = element.id.replace(/[a-z]*_[0-9]*_|[a-z]*/, '');
                    this.values = element.value;
                }
            }.bind(this));
        }
            
        // Put events to check select reloads 
        this.settings.each(function(element){
            Event.observe(element, 'change', this.configure.bind(this))
        }.bind(this));

        // fill state
        this.settings.each(function(element){
            var attributeId = element.id.replace(/[a-z]*_[0-9]*_|[a-z]*/, '');
            if(attributeId && this.config.attributes[attributeId]) {
                element.config = this.config.attributes[attributeId];
                element.attributeId = attributeId;
                this.state[attributeId] = false;
            }
        }.bind(this))

        // Init settings dropdown
        var childSettings = [];
        for(var i=this.settings.length-1;i>=0;i--){
            var prevSetting = this.settings[i-1] ? this.settings[i-1] : false;
            var nextSetting = this.settings[i+1] ? this.settings[i+1] : false;
            if (i == 0){
                this.settings[i].disabled = false;
                this.fillSelect(this.settings[i])
            } else {
                this.settings[i].disabled = true;
            }
            $(this.settings[i]).childSettings = childSettings.clone();
            $(this.settings[i]).prevSetting   = prevSetting;
            $(this.settings[i]).nextSetting   = nextSetting;
            childSettings.push(this.settings[i]);
        }

        // Set values to inputs
        this.configureForValues();
        document.observe("dom:loaded", this.configureForValues.bind(this));
    
};

//Product.Config.prototype.getAttributeOptions = function(attributeId,element){
//     var productId = element.id.match(/[a-z]*_([0-9]*)_[0-9]*/, '');
//       productId=productId[1];
//       if (spConfig[productId]){
//           console.log(spConfig[productId].config.attributes[attributeId].options);
//           alert(spConfig[productId].config.attributes[attributeId]);
//           return spConfig[productId].config.attributes[attributeId].options;
//       }
//       else
//        if(element.config.options){
//            return element.config.options;
//        }
//    };

//
//Product.Config.prototype.getInScopeProductIds = function(optionalAllowedProducts) {
//
//    var childProducts = this.config.childProducts;
//    var allowedProducts = [];
//
//    if ((typeof optionalAllowedProducts != 'undefined') && (optionalAllowedProducts.length > 0)) {
//       // alert("starting with: " + optionalAllowedProducts.inspect());
//        allowedProducts = optionalAllowedProducts;
//    }
//
//    for(var s=0, len=this.settings.length-1; s<=len; s++) {
//        if (this.settings[s].selectedIndex <= 0){
//            break;
//        }
//        var selected = this.settings[s].options[this.settings[s].selectedIndex];
//        if (s==0 && allowedProducts.length == 0){
//            allowedProducts = selected.config.allowedProducts;
//        } else {
//           // alert("merging: " + allowedProducts.inspect() + " with: " + selected.config.allowedProducts.inspect());
//            allowedProducts = allowedProducts.intersect(selected.config.allowedProducts).uniq();
//           // alert("to give: " + allowedProducts.inspect());
//        }
//    }
//
//    //If we can't find any products (because nothing's been selected most likely)
//    //then just use all product ids.
//    if ((typeof allowedProducts == 'undefined') || (allowedProducts.length == 0)) {
//        productIds = Object.keys(childProducts);
//    } else {
//        productIds = allowedProducts;
//    }
//    return productIds;
//};
// Product.Config.prototype.configureForValues = function (productId) {
//        if (this.values) {
//            this.settings[productId].each(function(element){
//                var attributeId = element.attributeId;
//                element.value = (typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId];
//                this.configureElement(element);
//            }.bind(this));
//        }
//    };
//
//    Product.Config.prototype.configure= function(event){
//        var element = Event.element(event);
//        this.configureElement(element);
//    };
//
//  Product.Config.prototype.configureElement = function(element) {
//        this.reloadOptionLabels(element);
//        if(element.value){
//            this.state[element.config.id] = element.value;
//            if(element.nextSetting){
//                element.nextSetting.disabled = false;
//                this.fillSelect(element.nextSetting);
//                this.resetChildren(element.nextSetting);
//            }
//        }
//        else {
//            this.resetChildren(element);
//        }
//        this.reloadPrice(element);
//    };
//
//    Product.Config.prototype.reloadOptionLabels= function(element){
//        var selectedPrice;
//        if(element.options[element.selectedIndex].config && !this.config.stablePrices){
//            selectedPrice = parseFloat(element.options[element.selectedIndex].config.price)
//        }
//        else{
//            selectedPrice = 0;
//        }
//        for(var i=0;i<element.options.length;i++){
//            if(element.options[i].config){
//                element.options[i].text = this.getOptionLabel(element.options[i].config, element.options[i].config.price-selectedPrice);
//            }
//        }
//    };
//
//   Product.Config.prototype.resetChildren= function(element){
//        if(element.childSettings) {
//            for(var i=0;i<element.childSettings.length;i++){
//                element.childSettings[i].selectedIndex = 0;
//                element.childSettings[i].disabled = true;
//                if(element.config){
//                    this.state[element.config.id] = false;
//                }
//            }
//        }
//    };
//
//    Product.Config.prototype.getOptionLabel=function(option, price){
//        var price = parseFloat(price);
//        if (this.taxConfig.includeTax) {
//            var tax = price / (100 + this.taxConfig.defaultTax) * this.taxConfig.defaultTax;
//            var excl = price - tax;
//            var incl = excl*(1+(this.taxConfig.currentTax/100));
//        } else {
//            var tax = price * (this.taxConfig.currentTax / 100);
//            var excl = price;
//            var incl = excl + tax;
//        }
//
//        if (this.taxConfig.showIncludeTax || this.taxConfig.showBothPrices) {
//            price = incl;
//        } else {
//            price = excl;
//        }
//
//        var str = option.label;
//        if(price){
//            if (this.taxConfig.showBothPrices) {
//                str+= ' ' + this.formatPrice(excl, true) + ' (' + this.formatPrice(price, true) + ' ' + this.taxConfig.inclTaxTitle + ')';
//            } else {
//                str+= ' ' + this.formatPrice(price, true);
//            }
//        }
//        return str;
//    }
//
//    Product.Config.prototype.formatPrice = function(price, showSign){
//        var str = '';
//        price = parseFloat(price);
//        if(showSign){
//            if(price<0){
//                str+= '-';
//                price = -price;
//            }
//            else{
//                str+= '+';
//            }
//        }
//
//        var roundedPrice = (Math.round(price*100)/100).toString();
//
//        if (this.prices && this.prices[roundedPrice]) {
//            str+= this.prices[roundedPrice];
//        }
//        else {
//            str+= this.priceTemplate.evaluate({price:price.toFixed(2)});
//        }
//        return str;
//    };
//
//    Product.Config.prototype.clearSelect= function(element){
//        for(var i=element.options.length-1;i>=0;i--){
//            element.remove(i);
//        }
//    };
//
//    
//
//    Product.Config.prototype.reloadPrice = function(){
//        if (this.config.disablePriceReload) {
//            return;
//        }
//        var price    = 0;
//        var oldPrice = 0;
////        var productId = element.id.match(/[a-z]*_([0-9]*)_[0-9]*/, '');
////        productId=productId[1];
//        for(var i=this.settings.length-1;i>=0;i--){
//            var selected = this.settings[i].options[this.settings[i].selectedIndex];
//            if(selected.config){
//                price    += parseFloat(selected.config.price);
//                oldPrice += parseFloat(selected.config.oldPrice);
//            }
//        }
//
//        optionsPrice[this.config.productId].changePrice('config', {'price': price, 'oldPrice': oldPrice});
//        optionsPrice[this.config.productId].reload();
//
//        return price;
//
//        if($('product-price-'+this.config.productId)){
//            $('product-price-'+this.config.productId).innerHTML = price;
//        }
//        this.reloadOldPrice();
//    };
//
//    Product.Config.prototype.reloadOldPrice = function(){
//        if (this.config.disablePriceReload) {
//            return;
//        }
//        if ($('old-price-'+this.config.productId)) {
//
//            var price = parseFloat(this.config.oldPrice);
//            for(var i=this.settings.length-1;i>=0;i--){
//                var selected = this.settings[i].options[this.settings[i].selectedIndex];
//                if(selected.config){
//                    price+= parseFloat(selected.config.price);
//                }
//            }
//            if (price < 0)
//                price = 0;
//            price = this.formatPrice(price);
//
//            if($('old-price-'+this.config.productId)){
//                $('old-price-'+this.config.productId).innerHTML = price;
//            }
//
//        }
//    };
//

//This triggers reload of price and other elements that can change
//once all options are selected
Product.Config.prototype.reloadPrice = function() {
    var childProductId = this.getMatchingSimpleProduct();
    var childProducts = this.config.childProducts;
    var usingZoomer = false;
    if(this.config.imageZoomer){
        usingZoomer = true;
    }

    if (childProductId){
        var price = childProducts[childProductId]["price"];
        var finalPrice = childProducts[childProductId]["finalPrice"];
        optionsPrice[this.config.productId].productPrice = finalPrice;
        optionsPrice[this.config.productId].productOldPrice = price;
        optionsPrice[this.config.productId].reload();
        optionsPrice[this.config.productId].reloadPriceLabels(true);
        optionsPrice[this.config.productId].updateSpecialPriceDisplay(price, finalPrice);
        this.updateProductShortDescription(childProductId);
        this.updateProductDescription(childProductId);
        this.updateProductName(childProductId);
        this.updateProductAttributes(childProductId);
        this.updateFormProductId(childProductId);
        this.addParentProductIdToCartForm(this.config.productId);
        this.showCustomOptionsBlock(childProductId, this.config.productId);
        if (usingZoomer) {
            this.showFullImageDiv(childProductId, this.config.productId);
        }else{
            this.updateProductImage(childProductId);
        }

    } else {
        var cheapestPid = this.getProductIdOfCheapestProductInScope("finalPrice");
        //var mostExpensivePid = this.getProductIdOfMostExpensiveProductInScope("finalPrice");
        var price = childProducts[cheapestPid]["price"];
        var finalPrice = childProducts[cheapestPid]["finalPrice"];
        optionsPrice[this.config.productId].productPrice = finalPrice;
        optionsPrice[this.config.productId].productOldPrice = price;
        optionsPrice[this.config.productId].reload();
        optionsPrice[this.config.productId].reloadPriceLabels(false);
        optionsPrice[this.config.productId].updateSpecialPriceDisplay(price, finalPrice);
        this.updateProductShortDescription(false);
        this.updateProductDescription(false);
        this.updateProductName(false);
        this.updateProductAttributes(false);
        this.showCustomOptionsBlock(false, false);
        if (usingZoomer) {
            this.showFullImageDiv(false, false);
        }else{
            this.updateProductImage(false);
        }
    }
};

//SCP: Forces the 'next' element to have it's optionLabels reloaded too
Product.Config.prototype.configureElement = function(element) {
    this.reloadOptionLabels(element);
    if(element.value){
        this.state[element.config.id] = element.value;
        if(element.nextSetting){
            element.nextSetting.disabled = false;
            this.fillSelect(element.nextSetting);
            this.reloadOptionLabels(element.nextSetting);
            this.resetChildren(element.nextSetting);
        }
    }
    else {
        this.resetChildren(element);
    }
    this.reloadPrice();
};
Product.OptionsPrice.prototype.reloadPriceLabels = function(productPriceIsKnown) {
    var priceFromLabel = '';
  //  var prodForm = $('product_addtocart_form');
 var formname='product_addtocart_form_'+this.productId;
 var prodForm = $(formname);
    if (!productPriceIsKnown && typeof spConfig != "undefined") {
        priceFromLabel = spConfig[this.productId].config.priceFromLabel;
    }

    var priceSpanId = 'configurable-price-from-' + this.productId;
    var duplicatePriceSpanId = priceSpanId + this.duplicateIdSuffix;

    if($(priceSpanId) && $(priceSpanId).select('span.configurable-price-from-label'))
        $(priceSpanId).select('span.configurable-price-from-label').each(function(label) {
        label.innerHTML = priceFromLabel;
    });

    if ($(duplicatePriceSpanId) && $(duplicatePriceSpanId).select('span.configurable-price-from-label')) {
        $(duplicatePriceSpanId).select('span.configurable-price-from-label').each(function(label) {
            label.innerHTML = priceFromLabel;
        });
    }
};
//Product.Config.prototype.updateFormProductId = function(productId){
//    if (!productId) {
//        return false;
//    }
//     var formname='product_addtocart_form_'+this.productId;
//  //  var currentAction = $('product_addtocart_form').action;
//  var currentAction = $(formname).action;
//    newcurrentAction = currentAction.sub(/product\/\d+\//, 'product/' + productId + '/');
////    $('product_addtocart_form').action = newcurrentAction;
////    $('product_addtocart_form').product.value = productId;
//$(formname).action = newcurrentAction;
//    $('product_addtocart_form').product.value = productId;
//};

Product.Config.prototype.addParentProductIdToCartForm = function(parentProductId) {
     var formname='product_addtocart_form_'+this.config.productId;
    if (typeof $(formname).cpid != 'undefined') {
        return; //don't create it if we have one..
    }
    var el = document.createElement("input");
    el.type = "hidden";
    el.name = "cpid";
    el.value = parentProductId.toString();
    //var formname='product_addtocart_form_'+this.productId;
    $(formname).appendChild(el);
};

Product.Config.prototype.updateFormProductId = function(productId){
    if (!productId) {
        return false;
    }
    var formname='product_addtocart_form_'+this.config.productId;
    
    var currentAction = $(formname).action;
    newcurrentAction = currentAction.sub(/product\/\d+\//, 'product/' + productId + '/');
   $(formname).action = newcurrentAction;
    $(formname).product.value = productId;
};
Product.OptionsPrice.prototype.updateSpecialPriceDisplay = function(price, finalPrice) {

    var formname='product_addtocart_form_'+this.productId;
 var prodForm = $(formname);

    var specialPriceBox = prodForm.select('p.special-price');
    var oldPricePriceBox = prodForm.select('p.old-price, p.was-old-price');
    var magentopriceLabel = prodForm.select('span.price-label');

    if (price == finalPrice) {
        specialPriceBox.each(function(x) {x.hide();});
        magentopriceLabel.each(function(x) {x.hide();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('old-price');
            x.addClassName('was-old-price');
        });
    }else{
        specialPriceBox.each(function(x) {x.show();});
        magentopriceLabel.each(function(x) {x.show();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('was-old-price');
            x.addClassName('old-price');
        });
    }
};

Product.Config.prototype.updateProductImage = function(productId) {
    var imageUrl = this.config.imageUrl;
    if(productId && this.config.childProducts[productId].imageUrl) {
        imageUrl = this.config.childProducts[productId].imageUrl;
    }

    if (!imageUrl) {
        return;
    }

    if($('image')) {
        $('image').src = imageUrl;
    } else {
        $$('#product_addtocart_form_'+this.productId+' p.product-image img').each(function(el) {
            var dims = el.getDimensions();
            el.src = imageUrl;
            el.width = dims.width;
            el.height = dims.height;
        });
    }
};
Product.Config.prototype.updateProductName = function(productId) {
    var productName = this.config.productName;
    if (productId && this.config.childProducts[productId].productName) {
        productName = this.config.childProducts[productId].productName;
    }
    $$('#product_addtocart_form_'+this.productId+'div.product-name h1').each(function(el) {
        el.innerHTML = productName;
    });
};

Product.Config.prototype.updateProductShortDescription = function(productId) {
    var shortDescription = this.config.shortDescription;
    if (productId && this.config.childProducts[productId].shortDescription) {
        shortDescription = this.config.childProducts[productId].shortDescription;
    }
    $$('#product_addtocart_form_'+this.productId+' div.short-description div.std').each(function(el) {
        el.innerHTML = shortDescription;
    });
};
//SCP: Forces price labels to be updated on load
//so that first select shows ranges from the start
document.observe("dom:loaded", function() {
    //Really only needs to be the first element that has configureElement set on it,
    //rather than all.
    var formname='product_addtocart_form_'+this.productId;
 var prodForm = $(formname);
    $(formname).getElements().each(function(el) {
        if(el.type == 'select-one') {
            if(el.options && (el.options.length > 1)) {
                el.options[0].selected = true;
                spConfig.reloadOptionLabels(el);
            }
        }
    });
});
