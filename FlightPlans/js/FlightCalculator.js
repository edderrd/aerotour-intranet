FlightCalculator = {

    magHeading: null,
    groundSpd: null,
    windCA: null,
    wca: null,
    crs: null,

    GndSpdCrsWca: function (windDir, windSpd, trueTrack, trueAS) {

        //These values are unknown and will be calculated
        wd = (Math.PI/180) * windDir;
        hd = (Math.PI/180) * trueTrack;
        this.groundSpd = Math.round(Math.sqrt(Math.pow(windSpd, 2) + Math.pow(trueAS, 2)- 2 * windSpd * trueAS * Math.cos(hd-wd)));
        this.wca = Math.atan2(windSpd * Math.sin(hd-wd), trueAS-windSpd * Math.cos(hd-wd));
        this.windCA = Math.round((180/Math.PI) * this.wca);
        this.crs = (hd + this.wca) % (2 * Math.PI);
        this.magHeading = Math.round((180/Math.PI) * this.crs);
    },

    parseWinDirVelocity: function(winDirVelocity) {
        var dirVelocity = winDirVelocity.split("/");
        
        return dirVelocity;
    }    
}
