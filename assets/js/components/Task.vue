<template>
    <div class="task row">
        <div class="container col-11 underline">
            <span :id="'name' + id" class="task-name col-12" :class="checked === true ? 'check' : ''" @click="focusModify()">{{ name }}</span>

            <!-- Modification du nom de la tâche -->
            <form method="post" :id="'modify' + id" class="task-name modify">
                <input type="hidden" name="modify" :value="id">
                <input type="text" :id="'nameInput' + id" name="name" :placeholder="name" class="col-10" @focusout="focusOutModify()">
                <input type="submit" class="btn btn-primary btn-sm" value="modifier" @click="focusOutModify()">
            </form>
        </div>

        <!-- Check/uncheck et suppression d'une tâche -->
        <div id="form" class="col-1">
            <form method="post" id="check">
                <input type="hidden" name="check" :value="id">
                <input v-if="checked" type="submit" value="" class="checked-btn">
                <input v-else type="submit" value="" class="unchecked-btn">
            </form>
            <form method="post" id="delete">
                <input type="hidden" name="delete" :value="id">
                <input type="submit" value="" class="delete-btn">
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "task",
    props: {
        name: {
            type: String,
            required: true
        },
        checked: {
            type: Boolean,
            required: true
        },
        id: {
            type: Number,
            required: true
        }
    },
    methods: {
        focusModify() {
            document.querySelector("#modify" + this.id).style.display = "block";
            document.querySelector("#nameInput" + this.id).focus();
            document.querySelector("#name" + this.id).style.display = "none";
        },
        focusOutModify() {
            function focusOut(id) {
                document.querySelector("#name" + id).style.display = "block";
                document.querySelector("#modify" + id).style.display = "none";
            }
            setTimeout(focusOut, 300, this.id);
        }
    },
    mounted: function() {
        document.querySelector("#modify" + this.id).style.display = "none";
    }
}
</script>

<style scoped>
    .check {
        text-decoration: line-through;
    }

    .task {
        margin-bottom: 20px;
    }

    .row {
        --bs-gutter-x: 0;
    }

    .modify {
        display: none;
    }

    .modify input[type=submit] {
        width: 15.5%;
        float: right;
    }

    .task-name {
        display: block;
        text-align: start;
        cursor: pointer;
    }

    .task .underline {
        border-bottom: 2px solid lightgray;
        border-radius: 0;
        padding: 0;
    }

    .delete-btn {
        background: url("../../icons/delete.svg") no-repeat;
        background-position-y: 3px;
        width: 100%;
        border: none;
    }

    .checked-btn {
        background: url("../../icons/checked.svg") no-repeat;
        background-position-y: 3px;
        width: 100%;
        border: none;
    }


    .unchecked-btn {
        background: url("../../icons/unchecked.svg") no-repeat;
        background-position-y: 3px;
        width: 100%;
        border: none;
    }

    #check, #delete {
        display: inline-block;
        width: 24px;
        transform: scale(0, 0);
        transition: transform .1s ease-in-out;
    }

    #check {
        margin-left: 10px;
    }

    .task:hover > div #check, .task:hover > div #delete{
        transform: scale(1, 1);
    }
</style>