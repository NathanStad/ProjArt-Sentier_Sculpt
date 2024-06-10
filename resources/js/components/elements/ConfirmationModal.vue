<template>
    <div class="modal">
      <div class="modal-content">
        <p>{{ message }}</p>
        <div class="buttons">
            <button @click="confirm" id="confirm">Oui</button>
            <button @click="cancel" id="annuler">Non</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      message: String,
      onConfirm: Function,
      onCancel: Function,
      type:Boolean
    },
    methods: {
      confirm() {
        this.onConfirm();
      },
      cancel() {
        this.onCancel();
      }
    }
  };
  </script>
  
  <style scoped>
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 50;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background-color: white;
    width: 65%;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    font-weight: 500;
    border: 1px solid var(--primary);

  }
  .modal-content p {
    height: 50%;
    padding: 15px;
    text-align: center;
  }
  
  .buttons {
    display: flex;
    justify-content: space-between;
    height: 50%;
    position: relative;

  }
  
  button {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    width: 50%;
    font-weight: 500;
    color: white;
  }
  #annuler{
    background-color: var(--primary);
  }
  #confirm{
    border-top: 1px solid var(--primary);
    color: var(--primary);
    background-color: white;
  }
  </style>
  