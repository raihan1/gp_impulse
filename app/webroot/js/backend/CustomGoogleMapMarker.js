function CustomMarker(latlng, map, args) {
    this.latlng = latlng;
    this.args = args;
    this.setMap(map);
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function () {

    var self = this;
    var div = this.div;

    if (!div) {
        div = this.div = document.createElement('div');

        div.className = 'marker';
        div.style.position = 'absolute';
        div.style.cursor = 'pointer';
        div.style.width = 'auto';
        div.style.height = 'auto';
        div.style.padding = '0px 2px';
        div.style.background = '#3175af';
        div.style.color = '#FFF';
        //div.innerHTML = self.args['name'];
        if(self.args['name'] != '') {
            div.innerHTML = (self.args.name).match(/\b\w/g).join('');
        }

        /*if (typeof (self.args.marker_id) !== 'undefined') {
           div.dataset.marker_id = self.args.marker_id;
        }*/

        google.maps.event.addDomListener(div, "click", function (event) {
            google.maps.event.trigger(self, "click");
        });

        var panes = this.getPanes();
        panes.overlayImage.appendChild(div);
    }

    var point = this.getProjection().fromLatLngToDivPixel(this.latlng);

    if (point) {
        div.style.left = (point.x - (div.offsetWidth)/2) + 'px';
        div.style.top = (point.y - 0) + 'px';
    }
};

CustomMarker.prototype.remove = function () {
    if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
    }
};

CustomMarker.prototype.getPosition = function () {
    return this.latlng;
};