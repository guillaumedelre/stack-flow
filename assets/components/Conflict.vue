<template>
    <div>
        <i :class="conflictIcon()"></i>
        {{ !hasConflicts ? 'all good' : 'conflicts' }}
    </div>
</template>

<script>
export default {
    props: {
        hasConflicts: {
            type: Boolean,
            required: true
        },
        fontAwesomeStyle: {
            type: String,
            required: true
        }
    },
    methods: {
        conflictIcon() {
            return [this.fontAwesomeStyle, 'mr-2', (this.hasConflicts ? 'fa-exclamation-triangle' : 'fa-award')].join(' ')
        }
    }
}
</script>
