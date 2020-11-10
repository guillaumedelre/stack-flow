<template>
    <div :class="doNotMergeBitchCss">
        <div class="card-body p-2">
            <div class="row">
                <MergeRequestHeading
                    :author="mergeRequest.author"
                    :do-not-merge-bitch="mergeRequest.doNotMergeBitch"
                    :redmine-id="mergeRequest.redmineId"
                    :font-awesome-style="fontAwesomeStyle"
                    :source-branch="mergeRequest.sourceBranch"
                />
                <div class="col">
                    <MergeRequestComplexity
                        :css-background-color="'bg-' + mergeRequest.author.username"
                        :complexity="mergeRequest.complexity"
                    />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <MergeRequestProgress
                    :progress="progress"
                    :css-background-color="'bg-' + mergeRequest.author.username"
                />
            </div>
            <div style="position:absolute" class="container-fluid mt-2 text-light text-center">
                <div class="row">
                    <div v-for="reviewer in reviewers()" class="col">
                        <MergeRequestReview
                            :reviewer="reviewer"
                            :font-awesome-style="fontAwesomeStyle"
                            :merge-request="mergeRequest"
                        />
                    </div>
                    <div class="col">
                        <MergeRequestDiscussion
                            :font-awesome-style="fontAwesomeStyle"
                            :unresolved-blocking-discussions="mergeRequest.unresolvedBlockingDiscussions"
                        />
                    </div>
                    <div class="col">
                        <MergeRequestPipeline
                            :font-awesome-style="fontAwesomeStyle"
                            :icon="pipelineStatusIcon(mergeRequest.pipeline.status)"
                            :status="mergeRequest.pipeline.status"
                        />
                    </div>
                    <div class="col">
                        <MergeRequestConflict
                            :font-awesome-style="fontAwesomeStyle"
                            :has-conflicts="mergeRequest.hasConflicts"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MergeRequestHeading from './Heading.vue'
import MergeRequestComplexity from './Complexity.vue'
import MergeRequestProgress from './Progress.vue'
import MergeRequestReview from './Review.vue'
import MergeRequestDiscussion from './Discussion.vue'
import MergeRequestPipeline from './Pipeline.vue'
import MergeRequestConflict from './Conflict.vue'

export default {
    components: {
        MergeRequestHeading,
        MergeRequestComplexity,
        MergeRequestProgress,
        MergeRequestReview,
        MergeRequestDiscussion,
        MergeRequestPipeline,
        MergeRequestConflict,
    },
    props: {
        mergeRequest: {
            type: Object,
            required: true
        }
    },
    computed: {
        doNotMergeBitchCss() {
            return this.mergeRequest.doNotMergeBitch ? 'dnmb' : ''
        },
        progress () {
            return this.mergeRequest.progress
        },
        fontAwesomeStyle() {
            return window.AppConfig.fontAwesomeStyle
        }
    },
    methods: {
        reviewers() {
            let filtered = [];
            window.AppConfig.developers.forEach((developer) => {
                if (developer.data.username !== this.mergeRequest.author.username) {
                    filtered.push(developer)
                }
            })
            return filtered;
        },
        pipelineStatusIcon(status) {
            var icon = null;
            switch (status) {
                case 'success':
                    icon = 'fa-check-circle';
                    break;
                case 'scheduled':
                    icon = 'fa-calendar-check';
                    break;
                case 'failed':
                    icon = 'fa-times-circle';
                    break;
                case 'canceled':
                case 'skipped':
                    icon = 'fa-minus-circle';
                    break;
                case 'pending':
                case 'preparing':
                case 'waiting_for_resource':
                    icon = 'fa-spin fa-spinner-third';
                    break;
                case 'running':
                    icon = 'fa-spin fa-cog';
                    break;
                case 'created':
                    icon = 'fa-sparkles';
                    break;
            }
            return icon;
        }
    }
}
</script>
