import Vue from 'vue';
import Axios from 'axios';

var app = new Vue({
    el: '#app',
    data: {
        title: '',
        url: '',
        description: '',
        private: false,
        tags: '!!!!Funktioniert noch nicht!!!!',
        version: '',
        updateBookmarklet: false,
        notice: '',
        flash: 'error'
    },
    methods: {
        saveBookmark: function () {
            var instance = Axios.create({
                baseURL: bookmarkManager.rest_endpoint,
                timeout: 5000,
                headers: {'X-WP-Nonce': bookmarkManager.rest_nonce}
            });

            var vm = this;
            instance.post('/wp/v2/bookmarks', {
                title: this.title,
                content: this.description,
                status: this.private ? 'private' : 'publish',
                author: bookmarkManager.user_id,
                meta: {
                    _bookmark_manager_link: this.url
                }
            })
                .then(function (response) {
                    console.log(response);
                    vm.notice = bookmarkManager.i18n.bookmarkSaved;
                    vm.flash = 'success';
                    setTimeout(function() {
                        window.close();
                    }, 2000);
                })
                .catch(function (error) {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log(error.request);
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                    console.log(error.config);
                    vm.notice = bookmarkManager.i18n.error;
                    vm.flash = 'error';
                });

        }
    },
    mounted: function () {
        console.log(this.version < bookmarkManager.version);
        if ( this.version < bookmarkManager.version ) {
            this.updateBookmarklet = true;
        }
    },
    created: function() {
        // `this` points to the vm instance
        function getSearchParameters() {
            var prmstr = window.location.search.substr(1);
            return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
        }

        function transformToAssocArray(prmstr) {
            var params = {};
            var prmarr = prmstr.split("&");
            for (var i = 0; i < prmarr.length; i++) {
                var tmparr = prmarr[i].split("=");
                params[tmparr[0]] = tmparr[1];
            }
            return params;
        }

        var params = getSearchParameters();

        this.title = decodeURIComponent(params.t);
        this.url = decodeURIComponent(params.u);
        let description = decodeURIComponent(params.s);
        if (description !== 'undefined') {
            this.description = description;
        }
        this.version = params.v;
    }
})
