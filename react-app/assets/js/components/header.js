import React,{Component} from 'react';
import 'jquery'
import 'popper.js'
import 'bootstrap'

    const Header = (props) => {

        return (
            <header className={'bg-warning'}>
                <h1 className={'text-center text-white'}>Logo</h1>
                <div className={'form-group col-md-6 col-sm-12 offset-md-3'}>
                    <input
                        type={'text'}
                        className={"form-control"}
                        placeholder={'Enter some text'}
                        onChange={props.keywords}
                    />
                </div>
            </header>

        )
    }


export default Header;