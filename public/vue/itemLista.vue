<template>
    <li>{{item.texto}}</li>
</template>
<script>
    export default {
        name: 'item-lista',
        props:['item']
    }
</script>
<style>
   li{
        color:red
    }
</style>