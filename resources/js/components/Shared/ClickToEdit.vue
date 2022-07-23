<template>
    <div>
        <label v-if="label" for="click-to-edit">{{ label }}</label>
        <input type="text"
               id="click-to-edit"
               v-model="valueLocal"
               @change="update($event)"
               @blur="update($event)"
               @keyup.enter="update($event)"
               class="mb-0 w-100"
               :placeholder="placeholder"
        />
    </div>
</template>

<script>
    export default {
        name: 'click-to-edit',
        props: {
            canEdit: {
                type: Boolean,
                default: false
            },
            value: {
                type: String,
                required: true
            },
            placeholder: {
                type: String,
                default: ''
            },
            label: {
                type: String,
            }
        },

        data() {
            return {
                edit: false,
                valueLocal: this.value
            }
        },

        methods: {
            update(event) {
                if (event.target.value !== '') {
                    this.valueLocal = event.target.value;
                }
                this.$emit('input', this.valueLocal);
            },
        },

        watch: {
            value: function () {
                this.valueLocal = this.value;
            }
        },

        directives: {
            focus: {
                inserted(el) {
                    el.focus()
                }
            }
        }

    }
</script>
