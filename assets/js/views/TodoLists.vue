<template>
    <div class="container">
        <todo-list
            v-for="item in items"
            :name="item.name"
            :nb-of-tasks="item.nbOfTasks"
            :nb-of-validated-tasks="item.nbOfValidatedTasks"
            :id="item.id"
            :key="item.key"
        />

        <!-- Création d'une nouvelle liste -->
        <button type="button" id="new" class="btn btn-primary btn-sm" @click="focusName()">
            <img src="../../icons/add.svg" alt="Créer une nouvelle liste">
            nouvelle liste
        </button>
        <form method="post" id="create">
            <input type="text" name="name" id="name" @focusout="focusOutName()">
            <input type="submit" class="btn btn-primary btn-sm" value="Créer" @click="focusOutName()">
        </form>
    </div>
</template>

<script>
import TodoList from "../components/TodoList";

export default {
    name: "todo-lists",
    components: {
        TodoList
    },
    methods: {
        focusName() {
            document.querySelector("#create").style.display = "block";
            document.querySelector("#name").focus();
            document.querySelector("#new").style.display = "none";
        },
        focusOutName() {
            // document.querySelector("#create input[type=submit]").focus();
            setTimeout(function() {
                document.querySelector("#create").style.display = "none";
                document.querySelector("#new").style.display = "block";
            }, 300);
        }
    },
    data() {
        return {
            items: null
        }
    },
    beforeMount: function() {
        this.items = JSON.parse(document.getElementById("app").attributes["data-items"].value);
    }
}

</script>

<style scoped>
    .container {
        margin-top: 2vw;
    }

    form {
        display: none;
        /* display: inline-block */
    }

    input[type=submit] {
        position: relative;
        vertical-align: baseline;
        top: -1px;
        margin-left: 9px;
    }
</style>