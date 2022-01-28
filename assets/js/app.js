import Vue from 'vue';

import TodoLists from "./views/TodoLists";
import Tasks from "./views/Tasks";

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {
        TodoLists,
        Tasks
    }
});