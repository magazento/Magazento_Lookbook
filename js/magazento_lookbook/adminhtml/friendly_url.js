IgalleryFriendlyUrl = Class.create();
IgalleryFriendlyUrl.prototype = {
    customUrl: false,
    nameField: null,
    frindlyUrlField: null,
    initialize : function(nameId, friendlyUrlId) {
        this.nameField = $(nameId);
        this.frindlyUrlField = $(friendlyUrlId);
        
        this.nameField.observe('change', this.onChangedName);
        this.frindlyUrlField.observe('keyup', this.onChangedFriendlyUrl);
    },
    onChangedName: function(event) {
        if(!igalleryFriendlyUrl.customUrl) {
            igalleryFriendlyUrl.frindlyUrlField.value = igalleryFriendlyUrl.parseUrl(igalleryFriendlyUrl.nameField.value)
        }
    },
    onChangedFriendlyUrl: function(event) {
        
    },
    parseUrl: function(url) {
        url = url.replace(/ /g, '_');
        url = url.replace(/\W/g, '');
        url = url.replace(/_/g, '-');
        url = url.toLowerCase();
        return url;
    }
}

var igalleryFriendlyUrl;
document.observe('dom:loaded', function() { 
    igalleryFriendlyUrl = new IgalleryFriendlyUrl('name', 'friendly_url');
});