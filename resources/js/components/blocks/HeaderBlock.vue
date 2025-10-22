<template>
    <table style="width: 100%;">

        <tbody>
        <tr>

            <td
                class=""
                :style="{
    'background-image': `url(${hotelFeaturedImage})`,
    'background-repeat': 'no-repeat',
    'background-position': 'center center',
    'background-size': 'cover',
    'height': '250px'}" align=" center
                " bgcolor="" valign="top">
                <table
                    style="width: 100%; background: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);">
                    <tr>
                        <td style="height: 150px">

                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 16px">
                            <table>
                                <tr>
                                    <td>
                                        <img :src="props.hotelLogo" alt="hotel"
                                             style="width: 100px; height: auto; border-radius: 4px; object-fit: contain"/>

                                    </td>
                                    <td>
                                        <p
                                            style="color: white; font-size: 24px; font-weight: bold; margin-left: 16px">
                                            {{ props.hotelName }}</p>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>

const props = defineProps({
    'hotelLogo': {
        type: String,
        default: 'https://via.placeholder.com/100x50?text=Logo',
    },
    'hotelName': {
        type: String,
        default: 'Hotel Name',
    },
    'hotelFeaturedImage': {
        type: String,
        default: 'https://via.placeholder.com/600x250?text=Featured+Image',
    },
})
// This block has no props or emits, so the script is empty.
</script>
