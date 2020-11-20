<template>
    <div v-if="mergeRequests.length" class="container-fluid">
        <MergeRequestItem
            ref="mergeRequestItem"
            v-for="mergeRequest in mergeRequests"
            :key="mergeRequest.id"
            :merge-request="mergeRequest"
            class="mt-2"
        />
    </div>
</template>

<script>
import MergeRequestItem from './Item.vue'
const request = require('sync-request');

export default {
    components: {
        MergeRequestItem
    },
    created: function () {
        const updateUrl = new URL(window.AppConfig.mercure.url);
        updateUrl.searchParams.append('topic', window.AppConfig.mercure.updateTopic);
        const updateEventSource = new EventSource(updateUrl);
        updateEventSource.onmessage = (e) => this.watch(JSON.parse(e.data))

        const deleteUrl = new URL(window.AppConfig.mercure.url);
        deleteUrl.searchParams.append('topic', window.AppConfig.mercure.deleteTopic);
        const deleteEventSource = new EventSource(deleteUrl);
        deleteEventSource.onmessage = (e) => this.remove(JSON.parse(e.data))
    },
    data() {
        return {
            mergeRequests: JSON.parse(request('GET', window.AppConfig.fetch).getBody('utf8'))['hydra:member']
        }
    },
    methods: {
        watch (mergeRequest) {
            let isNew = true;
            for (let i=0; i<this.mergeRequests.length; i++) {
                if (this.mergeRequests[i].gitlabId === mergeRequest.gitlabId) {
                    this.$set(this.mergeRequests, i, mergeRequest)
                    isNew= false
                }
            }
            if (isNew) {
                this.$set(this.mergeRequests, this.mergeRequests.length, mergeRequest)
            }
        },
        remove (collection) {
            for (let i = 0; i < collection['orphans'].length; i++ ) {
                this.mergeRequests.forEach((mergeRequest, index) => {
                    if (collection['orphans'][i] === mergeRequest.gitlabId) {
                        this.mergeRequests.splice(index, 1);
                    }
                })
            }
        },
    }
}
</script>
