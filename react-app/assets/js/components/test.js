import React,{Component} from 'react';
import ReactDOM from 'react-dom';
import ReactHtmlParser, { processNodes, convertNodeToElement, htmlparser2 } from 'react-html-parser';
import 'bootstrap'
import '../css/app.css'

//Componets
import Header from './components/header';

export default class App extends Component{
    state = {
        loading: true,
        form: null
    };

    getForm = () => {
        fetch('http://127.0.0.1:8000/form')
            .then((response) => {
                return response.text()
            }).then((html) => {
            this.setState({form: html, loading: false})
        })

    }

    addClasses = () => {
        let form = document.querySelector('form');
        console.log(form);
        form.style.display = 'none';
    }

    message = () => {
        alert(1);
    }

    render() {
        return (
            <div>
                <Header/>
                <div onClick={this.addClasses}>Add class to form</div>
                {this.state.loading || !this.state.form ? (
                    <div>
                        <div onClick={this.getForm}>loading...</div>
                    </div>
                ) : (
                    <div>
                        {ReactHtmlParser(this.state.form)}
                    </div> )
                }
            </div>
        );
    }
}
ReactDOM.render(<App/>,document.getElementById('root'));