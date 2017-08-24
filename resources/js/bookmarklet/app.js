import Vue from 'vue';
import Axios from 'axios';

window.Event = new Vue();

Vue.component('tag-list', {
    template: `
        <div id="tags" class="field is-grouped is-grouped-multiline">
            <tag v-for="tag in tags" :key="tag.name" :name="tag.name"></tag>
        </div>
    `,

    props: {
        name: ''
    },

    data() {
        return {
            tags: []
        }
    },

    methods: {
        removeTag(name) {
            let tag = this.tags.find( tag => tag.name === name );
            Event.$emit('removeTagId', tag.id);
            // Event.$emit('removeTagId', )
            this.tags = this.tags.filter(tag => tag.name !== name);

        },

        addTag(name) {
            if (!this.tags.find(tag => tag.name === name)) {
                let newTag = {name: name};
                let tags = this.tags;

                let instance = Axios.create({
                    baseURL: bookmarkManager.rest_endpoint,
                    timeout: 5000,
                    headers: {'X-WP-Nonce': bookmarkManager.rest_nonce}
                });

                let slug = name.toLowerCase().replace(/ /g, '-');

                instance.get('/wp/v2/bookmark-tags', {
                    params: {
                        slug: slug,
                    }
                }).then(function (response) {
                    if (response.data.length > 0) {
                        newTag.id = response.data[0].id;
                        tags.push(newTag);
                        Event.$emit('newTagId', newTag.id);
                    } else {
                        instance.post('/wp/v2/bookmark-tags', {
                            name: name,
                        }).then(function (response) {
                            newTag.id = response.data.id;
                            tags.push(newTag);
                            Event.$emit('newTagId', newTag.id);
                        })
                    }
                });
            }
        }
    },

    created() {
        Event.$on('removeTag', this.removeTag);
        Event.$on('addTagToList', this.addTag);
    }

});

Vue.component('tag', {
    template: `
        <div class='control'>
            <div class='tags has-addons'>
                <span class='tag is-info'>{{ name }}</span>
                <a class='tag is-delete' @click="removeTag"></a>
            </div>
        </div>
    `,

    props: {
        name: ''
    },

    methods: {
        removeTag() {
            Event.$emit('removeTag', this.name)
        }
    }
});

var app = new Vue({
    el: '#app',
    data: {
        title: '',
        url: '',
        description: '',
        private: false,
        version: '',
        updateBookmarklet: false,
        isLoading: false,
        notice: '',
        flash: 'error',
        newTag: '',
        tags: []
    },

    methods: {
        saveBookmark: function () {
            this.isLoading = true;

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
                },
                "bookmark-tags": this.tags,
            }).then(function (response) {
                console.log(response);
                vm.notice = bookmarkManager.i18n.bookmarkSaved;
                vm.flash = 'success';
                setTimeout(function () {
                    window.close();
                }, 2000);
            }).catch(function (error) {
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

        },

        watchTag() {
            if (this.newTag.slice(-1) === ',' || event.which === 13) {

                if (this.newTag.slice(-1) === ',') {
                    this.newTag = this.newTag.slice(0, -1)
                }

                Event.$emit('addTagToList', this.newTag);
                this.newTag = '';
            }
        },

        addTagId(id) {
            this.tags.push(id);
        },

        removeTagId(removeId) {
            this.tags = this.tags.filter( id => id !== removeId );
        }
    },

    mounted: function () {
        if (this.version < bookmarkManager.version) {
            this.updateBookmarklet = true;
        }
    },

    created: function () {

        Event.$on('newTagId', this.addTagId);
        Event.$on('removeTagId', this.removeTagId);

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
