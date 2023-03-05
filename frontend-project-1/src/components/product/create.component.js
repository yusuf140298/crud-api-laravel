import React, {useState} from "react";
import { Form, Button, Row, Col } from "react-bootstrap";
import axios from "axios";
import Swal from "sweetalert2";
import { useNavigate } from "react-router-dom";


export default function CreateProduct(){
    const navigate = useNavigate();

    const [title, setTitle] = useState("")
    const [description, setDescription] = useState("")
    const [iamge, setImage] = useState()
    const [validationError, setValidationError] = useState({})

    const changeHandler = (event) => {
        setImage(event.target.files[0])
    }
}