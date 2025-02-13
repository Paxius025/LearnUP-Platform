import './bootstrap';

import Swal from 'sweetalert2';
import axios from "axios";

window.toggleBookmark = function (postId) {
    axios.post(`/favorite/${postId}`)
        .then(response => {
            const icon = document.getElementById(`bookmark-icon-${postId}`);

            // เปลี่ยนสีของ Bookmark
            icon.setAttribute("fill", response.data.bookmarked ? "red" : "white");

            // เพิ่ม Animation
            icon.classList.add("scale-110");
            setTimeout(() => {
                icon.classList.remove("scale-110");
            }, 200);
        })
        .catch(error => {
            console.error('There was an error!', error);
            alert('Something went wrong. Please try again later.');
        });
};
