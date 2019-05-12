import React,{Component} from 'react';
import 'jquery'
import 'popper.js'
import 'bootstrap'

class Header extends Component{

    getData() {
        return DATA.name;
    }

    getInputData(e) {
        let value = e.currentTarget.value;
        let h2 = document.getElementsByTagName('h2');
        console.log(value,h2);
        h2.innerText = value;

    }


    render() {
        const styles = {
            header: {
                background: '#03a9f4'
            },
            logo:{
                color:'#fff',
                textAlign: 'center'
            }
        }
        return (
            <header className={'bg-secondary'}>
                <h1 className={'text-center text-white'}>Logo</h1>
                <div className={'form-group col-md-6 col-sm-12 offset-md-3'}>
                    <input
                        type={'text'}
                        className={"form-control"}
                        placeholder={'Enter some text'}
                        onChange={ (e) => this.getInputData(e)}
                    />
                </div>
                <h2>Test</h2>
            </header>

        )
    }

}


export default Header;