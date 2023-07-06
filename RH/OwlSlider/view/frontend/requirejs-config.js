/**
 * Created By : Rohan Hapani
 */
// var config = {
//     paths: {
//         owlcarousel: "RH_OwlSlider/js/owl.carousel"
//     },
//     shim: {
//         owlcarousel: {
//             deps: ['jquery']
//         }
//     }
// };

var config = {
    // map: {
    //     '*': {
    //         'owlcarousel' : 'https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js',
    //     }
    // },
    // map: {
    //     '*': {
    //         'owlcarousel' : 'RH_OwlSlider/js/owl.carousel.min',
    //     }
    // },
    paths: {
        'owlcarousel': 'RH_OwlSlider/js/owl.carousel.min'
    },
    shim: {
        "owlcarousel": ["jquery"]
    },
    // Every page load
    deps: [
        'RH_OwlSlider/js/owl.carousel.min'
    ]
};