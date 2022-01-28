<template>
    <div class="container">
        <div id="breadcrumb"><a href="/">Listes</a> / {{ listName }}</div>
        <task
            v-for="item in items"
            :name="item.name"
            :checked="item.checked"
            :id="item.id"
            :key="item.id"
        />

        <!-- Création d'une nouvelle liste -->
        <button type="button" id="new" class="btn btn-primary btn-sm" @click="focusName()">
            <img src="../../icons/add.svg" alt="Créer une nouvelle liste">
            nouvelle tâche
        </button>
        <form method="post" id="create">
            <input type="text" name="name" id="name" @focusout="focusOutName()">
            <input type="submit" class="btn btn-primary btn-sm" value="Créer" @click="focusOutName()">
        </form>
    </div>
</template>

<script>
import Task from "../components/Task";

export default {
    name: "tasks",
    components: {
        Task
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
            items: null,
            listName: null
        }
    },
    beforeMount: function() {
        this.items = JSON.parse(document.getElementById("app").attributes["data-items"].value);
        this.listName = JSON.parse(document.getElementById("app").attributes["data-list-name"].value);
    }
}

</script>

<style scoped>
    #breadcrumb {
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    .container {
        margin-top: 2vw;
    }

    form {
        display: none;
    }

    input[type=submit] {
        position: relative;
        vertical-align: baseline;
        top: -1px;
        margin-left: 9px;
    }
</style>