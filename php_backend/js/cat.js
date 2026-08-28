//Import the THREE.js library
import * as THREE from "./Threejs.min.js";
//import * as THREE from "https://cdn.skypack.dev/three@0.129.0/build/three.module.js";

// To allow for importing the .gltf file
//import { GLTFLoader } from "https://cdn.skypack.dev/three@0.129.0/examples/jsm/loaders/GLTFLoader.js";

import { GLTFLoader } from "./GTLFLoader.min.js";

export function load3DCat(i) {
  let w = document.getElementById("container3D").offsetWidth;
  let h = document.getElementById("container3D").offsetHeight;
  //Create a Three.JS Scene
  const scene = new THREE.Scene();
  //create a new camera with positions and angles
  //const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  const camera = new THREE.PerspectiveCamera(60, w / h, 0.1, 1000);

  camera.rotation.set(0, 0, 0.15); //x,y,z

  //Keep the 3D object on a global variable so we can access it later
  let object;

  let selctedObj = i;

  //Set which object to render
  let objToRender;
  if (i == "cat") {
    objToRender = `models/cat/cat2.gltf`;
  } else if (i == "floppy") {
    objToRender = `models/floppy/floppy.gltf`;
  }

  //Instantiate a loader for the .gltf file
  const loader = new GLTFLoader();

  //Instantiate a new renderer and set its size
  const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true }); //Alpha: true allows for the transparent background
  //renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1;
  renderer.outputEncoding = THREE.sRGBEncoding;
  renderer.setSize(w, h);

  //Add the renderer to the DOM
  document.getElementById("container3D").appendChild(renderer.domElement);

  //Set how far the camera will be from the 3D model
  camera.position.z = selctedObj === "cat" ? 3.5 : 3;

  //Add lights to the scene, so we can actually see the 3D model
  const topLight = new THREE.DirectionalLight(0xffffff, 3); // (color, intensity)
  topLight.position.set(-40, 40, 20);
  // topLight.position.set(100,20, 100) //top-left-ish
  /*topLight.shadow.mapSize.width=4000;
  topLight.shadow.mapSize.height=4000;
  topLight.shadow.normalBias=0.1;
  topLight.shadow.bias=0.002;*/
  topLight.castShadow = true;

  scene.add(topLight);

  const topLight2 = new THREE.DirectionalLight(0x4287f5, 3); // (color, intensity)
  topLight2.position.set(50, 20, -50); //x-y-prof
  //topLight2.shadow.bias=0.004;
  topLight2.castShadow = true;

  scene.add(topLight2);
  /* visualizza sorgente luce
  const helper = new THREE.DirectionalLightHelper( topLight, 5 );
  scene.add( helper );
  
*/
  const ambientLight = new THREE.AmbientLight(
    0x333333,
    selctedObj === "cat" ? 3 : 3
  );
  scene.add(ambientLight);

  //Render the scene
  function animate() {
    requestAnimationFrame(animate);
    //Here we could add some code to update the scene, adding some automatic movement
    object.rotation.y += 0.01;

    renderer.render(scene, camera);
  }

  //Add a listener to the window, so we can resize the window and the camera
  window.addEventListener("resize", function () {
    let w2 = document.getElementById("container3D").offsetWidth;
    let h2 = document.getElementById("container3D").offsetHeight;
    /*camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);*/
    camera.aspect = w2 / h2;
    camera.updateProjectionMatrix();
    renderer.setSize(w2, h2);
  });

  //Load the file
  loader.load(
    //`models/${objToRender}/scene.gltf`,
    //`models/cat/cat2.gltf`,
    objToRender,
    function (gltf) {
      //If the file is loaded, add it to the scene
      object = gltf.scene;
      scene.add(object);
      object.rotation.y = -Math.PI / 2;
      object.rotation.x = -0.35;

      if (selctedObj == "floppy") {
        object.position.y -= 0.2;
      }

      //Start the 3D rendering
      animate();
    },
    function (xhr) {
      //While it is loading, log the progress
      console.log((xhr.loaded / xhr.total) * 100 + "% loaded");
    },
    function (error) {
      //If there is an error, log it
      console.error(error);
    }
  );
}
//load3DCat();
