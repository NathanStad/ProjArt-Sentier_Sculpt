<template lang="">
    <!-- Thèmes -->
    <div>
        <div v-for="item in items">
            <div><img src="{{item.img}}" alt="{{item.src}}" /></div>
            <p>{{ item.titre }}</p>
        </div>
    </div>
    <!-- Sentiers  -->
    <div>
        <div v-for="item in 3">
            <div></div>
            <div>
                <p></p>
                <p></p>
            </div>
        </div>
    </div>
    <!-- Destinations préférés -->
    <h3 v-if="destPref > 0">Destinations préférés</h3>
    <div v-if="destPref > 0">
        <div v-for="item in items">
            <img src="" alt="">
            <div>
                <p></p>
                <p></p>
            </div>
        </div>
    </div>
</template>
<script>
export default {};

</script>
<style lang=""></style>
