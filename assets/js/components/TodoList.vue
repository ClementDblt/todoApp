<template>
    <div class="todo-list">
        <div class="row">
            <a class="btn col-11" :href="path" role="button">
                    <span class="todo-list-name">{{ name }}</span>
                    <span class="progression">{{ progress }}</span>
            </a>

            <!-- Suppression d'une liste -->
            <form method="post" class="col-1">
                <input type="hidden" name="delete" :value="id">
                <input type="submit" value="" class="delete-btn">
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "todo-list",
    props: {
        name: {
            type: String,
            required: true
        },
        nbOfTasks: {
            type: Number,
            required: true
        },
        nbOfValidatedTasks: {
            type: Number,
            required: true
        },
        id: {
            type: Number,
            required: true
        }
    },
    computed: {
        path() {
            return "/tasks/" + this.id;
        },
        progress() {
            return this.nbOfValidatedTasks + "/" + this.nbOfTasks;
        }
    }
}
</script>

<style scoped>
    .todo-list {
        margin-bottom: 20px;
    }

    .row {
        --bs-gutter-x: 0;
    }

    .todo-list-name {
        text-align: start;
    }

    a {
        padding: 0;
        display: flex;
        justify-content: space-between;
        border-bottom: 2px solid lightgray;
        border-radius: 0;
        margin-right: 1.5vw
    }

    a:hover {
        border-bottom: 2px solid #0d6efd;
    }

    .progression {
        color: lightgray;
    }

    .delete-btn {
        background: url("../../icons/delete.svg") no-repeat;
        background-position-y: 3px;
        width: 100%;
        border: none;
    }

    form {
        width: 28px;
        transform: scale(0, 0);
        transition: transform .1s ease-in-out;
    }

    .row:hover > form {
        transform: scale(1, 1);
    }
</style>