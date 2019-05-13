import React from 'react';

const NewsItem = ({item}) => {
    console.log()
    return(
        <div className={"card mb-3"}>
            <div className={"card-header text-center text-warning"}>
                <h3>Title: {item.title}</h3>
            </div>
            <div className={"card-body"}>
                <p>{item.content}</p>
            </div>
        </div>
    )
};

export default NewsItem;
